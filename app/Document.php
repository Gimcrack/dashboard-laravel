<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nathanmac\Utilities\Parser\Parser;
use Barryvdh\DomPDF\Facade as PDF;

class Document extends Model
{
    /**
     * Make the model track revision changes
     */
    use \Venturecraft\Revisionable\RevisionableTrait;

    /**
     * The parsed xml object
     * @var [type]
     */
    public $parsed;


    /**
     * The parameters extracted from the parsed document
     * @var [type]
     */
    public $params;

    /**
     * Boot the model
     * @return [type] [description]
     */
    public static function boot()
    {
        parent::boot();
    }

    /**
     * The column that identifies the model
     * @return [type] [description]
     */
    public function identifiableName()
    {
        return $this->name;
    }

    /**
     * Track creations as revisions
     * @var [type]
     */
    protected $revisionCreationsEnabled = true;

    /**
     * The database table that the model references
     *
     * @var string
     */
    protected $table = 'documents';

    /**
     * The mass assignable fields
     *
     * @var array
     */
    protected $fillable = [
        'name'
      , 'description'
      , 'raw_file_path'
      , 'parsed_file_path'
      , 'people_id'
      , 'status'
    ];

    /**
     * Polymorphic relationship. Second parameter to morphOne/morphMany
     * should be the same as the prefix for the *_id/*_type fields.
     */
    public function recordLock()
    {
        return $this->morphOne('App\RecordLock', 'lockable');
    }

    /**
     * Polymorphic relationship. Second parameter to morphOne/morphMany
     * should be the same as the prefix for the *_id/*_type fields.
     */
    public function tags()
    {
        return $this->morphMany('App\Tag', 'taggable');
    }

    /**
     * A document belongs to one user
     * @method owner
     * @return [type] [description]
     */
    public function owner()
    {
        return $this->belongsTo('App\Person','people_id');
    }

    /**
     * Parse the xml document and extract the needed data
     * @method parseDocument
     * @return [type]        [description]
     */
    public function parse()
    {
      $xml_path = storage_path() . DIRECTORY_SEPARATOR . 'documents' . DIRECTORY_SEPARATOR;
      $xml_file = $this->raw_file_path;

      $xml_raw_text = file_get_contents( $xml_path . $xml_file );

      $parser = new Parser();

      $this->parsed = $parser->xml( $xml_raw_text );

      $this->params = [
        'file_name' => $this->getFileName(),
        'description' => $this->getDescription(),
        'file_type' => $this->getFileType(),
        'geometry_type' => $this->getGeometryType(),
        'attributes' => $this->getAttributes(),
        'responsible_parties' => $this->getResponsibleParties(),
        'construction_procedures' => $this->getContstructionProcedures(),
        'coverage_area' => $this->getCoverageArea(),
        'dates' => $this->getDates(),
        'date_last_updated' => @$this->parsed['mdDateSt'] ?: ''
      ];

      return $this->params;
    }

    /**
     * Process this whenever a file is uploaded
     * @method process
     * @return [type]  [description]
     */
    public function process()
    {
      $document = $this;
      $params = $this->parse();
      $replace = [
        'pubDate' => 'Date Published',
      ];


      $this->update(['status' => 'processed']);

      $pdf = PDF::loadView('documents.showpdf', compact('document', 'params', 'replace')  );
      return $pdf->save( storage_path('documents') . DIRECTORY_SEPARATOR . $this->parsed_file_path );
    }

    /**
     * get file name from parsed text
     * @method getFileName
     * @return [type]              [description]
     */
    public function getFileName()
    {
      return @$this->parsed['Esri']['DataProperties']['itemProps']['itemName'] ?: '';
    }

    /**
     * get description from parsed text
     * @method getFileName
     * @return [type]              [description]
     */
    public function getDescription()
    {
      return strip_tags( @$this->parsed['dataIdInfo']['idAbs'] ?: '' );
    }

    /**
     * Get file type
     * @method getFileType
     * @return [type]      [description]
     */
    public function getFileType()
    {
      return @$this->parsed['distInfo']['distFormat']['formatName'] ?: '';
    }

    /**
     * Get geometry type
     * @method getGeometryType
     * @return [type]          [description]
     */
    public function getGeometryType()
    {
      return @$this->parsed['dataIdInfo']['idCitation']['otherCitDet'] ?: '';
    }

    /**
     * Get attributes
     * @method getAttributes
     * @return [type]        [description]
     */
    public function getAttributes()
    {
      $return = [];

      foreach ($this->parsed['eainfo']['detailed']['attr'] as $attr) {
        @$return[ $attr['attalias'] ] = $attr['attrdef'] ?: '';
      }

      return $return;
    }

    /**
     * Get responsible parties
     * @method getResponsibleParties
     * @return [type]                [description]
     */
    public function getResponsibleParties()
    {
      $return = [];

      if (empty($this->parsed['dataIdInfo']['idCitation']['citRespParty'])) {
        return $return;
      }

      foreach ($this->parsed['dataIdInfo']['idCitation']['citRespParty'] as $party) {
        $roleCode = $party['role']['RoleCd']['@attributes']['value'];

        switch( $roleCode * 1) {
          case 6 : // originator
            $return[] = [
              'role' => 'Originator',
              'OrgName' => $party['rpOrgName'],
            ];
          break;

          case 7 : // originator
            $return[] = [
              'role' => 'Point of Contact',
              'OrgName' => $party['rpOrgName'],
            ];
          break;
        }
      }

      return $return;

    }


    /**
     * Get Construction Procedures
     * @method getContstructionProcedures
     * @return [type]                     [description]
     */
    public function getContstructionProcedures()
    {
      $return = [];

      if (empty($this->parsed['dqInfo']['dataLineage']['prcStep'])) {
        return $return;
      }

      foreach ($this->parsed['dqInfo']['dataLineage']['prcStep'] as $step) {
        @$return[] = $step['stepDesc'];
      }

      return $return;

    }

    /**
     * Get Coverage Area
     * @method getCoverageArea
     * @return [type]          [description]
     */
    public function getCoverageArea()
    {
      foreach( $this->parsed['dataIdInfo']['dataExt'] as $data) {
        if (!empty($data['exDesc'])) {
          return $data['exDesc'];
        }
      }
      return '';
    }

    /**
     * Get Dates
     * @method getDates
     * @return [type]   [description]
     */
    public function getDates()
    {
      return @$this->parsed['dataIdInfo']['idCitation']['date'] ?: [];
    }
}
