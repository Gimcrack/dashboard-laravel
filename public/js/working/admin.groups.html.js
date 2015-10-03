// extend the application views
_.extend( jApp.views.admin, {

	groups : function() {

		_.extend( jApp.oG.admin, {

			groups : new jGrid({
				table : 'groups',
				columnFriendly : 'name',
				gridHeader : {
					icon : 'fa-users',
					headerTitle : 'Manage Groups',
					helpText : "<strong>Note:</strong> Manage Groups Here"
				},
				tableBtns : {
					new : {
						label : 'New Group',
					},
				},
				columns : [ 				// columns to query
					"id",
					"name",
					"description",
					"users",
					"modules"
				],
				hidCols : [					// columns to hide

				],
				headers : [ 				// headers for table
					"ID",
					"Name",
					"Description",
					"Users",
					"Modules"
				],
				templates : { 				// html template functions

					"id" : function(value) {
						var temp = '0000' + value;
						return temp.slice(-4);
					},

					"users" : function(arr) {
						return _.pluck(arr, 'name').join(', ');
					},

					"modules" : function(arr) {
						return _.pluck(arr, 'name').join(', ');
					},

					"created_at" : function(value) {
						return date('Y-m-d', strtotime(value));
					},

					"updated_at" : function(value) {
						return date('Y-m-d', strtotime(value));
					}

				},
				linkTables : [
						{
							selectName : 'users',
							selectLabel : 'Users',
							model : 'User',
							valueColumn : 'id',
							labelColumn : 'name'
						},
						{
							selectName : 'modules',
							selectLabel : 'Modules',
							model : 'Module',
							valueColumn : 'id',
							labelColumn : 'name'
						}
				],
				rowsPerPage : 10,			// rows per page to display on grid
				pageNum	: 1,				// current page number to display
			})
		})
	}
});
