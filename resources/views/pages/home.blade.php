@extends('app')

@section('content')

<!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>{{ env('APP_TITLE') }}</h1>
        <p>Utilities and helpers for the Matsu Borough IT Department.</p>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-6">
          <div class="panel panel-info">
            <div class="panel-heading">
              <h2>Announcements</h2>
            </div>
            <div class="panel-body">
              <div class="alert alert-success">
                <strong> <i class="fa fa-fw fa-info"></i> v2.0 Production Beta is Live! </strong>
                <br/>
                Please inform Jeremy of any issues you experience.
              </div>

              <!-- <div class="alert alert-warning">
                <strong> <i class="fa fa-fw fa-info"></i> Notice to all employees: </strong> Efficiently unleash cross-media information without cross-media value. Quickly maximize timely deliverables for real-time schemas.
                Dramatically maintain clicks-and-mortar solutions without functional solutions.
              </div>

              <div class="alert alert-danger">
                <strong> <i class="fa fa-fw fa-info"></i> Notice to all employees: </strong>  Completely synergize resource taxing relationships via premier niche markets. Professionally cultivate one-to-one customer service with robust ideas.
                Dynamically innovate resource-leveling customer service for state of the art customer service.
              </div> -->
            </div>
          </div>
        </div>
       @unless( Auth::check() )
        <div class="col-md-6">
          <div class="panel panel-success">
            <div class="panel-heading">
              <h2>Login To Get Started</h2>
            </div>

            <div class="panel-body">
              @include('partials.adloginform')
            </div>

            <div class="panel-body">
              @include('partials.loginform')
            </div>
          </div>



        </div>
       @endunless

       @if( Auth::check() )
        <div class="col-md-6">
          <div class="panel panel-success">
            <div class="panel-heading">
              <h2> <i class="fa fa-fw fa-user"></i> Welcome {{ Auth::user()->person->name }}</h2>
            </div>
            <div class="panel-body">

              <div class="alert alert-info">

                <h3>User Menu</h3>
                <p>
                  Open the user menu (top right corner) to explore user functions.
                </p>

                <h3>Navigation Menu</h3>
                <p>
                  Open the main navigation menu (top left corner) to explore the site.
                </p>

                <p>
                  <button class="btn btn-primary btn-lg" type="button" onclick="$('.navbar-toggle').click()">Try It Now &raquo;</button>
                </p>
              </div>
            </div>
          </div>




        </div>
       @endif
      </div>

    </div> <!-- /container -->

@endsection
