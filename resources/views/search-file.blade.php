@extends("voyager::master")

@section('css')
    <link rel="stylesheet" href="{{ asset('/js/select2/select2.min.css') }}">
@endsection

@section('content')

<div class="container">
        <div class="row" style="padding: 10px" >
            <div class="col-12" style="padding-top: 60px">
                <input type="text" class="form-control" placeholder="Arama yap...">
            </div>
            <div class="row justify-content-center">
                <div class="col-sm-3">
                    <label for="select" style="">Author</label>
                    <select id="authors_" class="form-control" name="" id="">
                        <option value="-1">All</option>
                    </select>
                </div>

                <div class="col-sm-3">
                    <label for="datepicker" style="">File Date</label>
                    <div class="row" style="display: flex">
                        <input type="date" class="form-control" value="">
                        <div class="input-group-addon" style="width:50px">to</div>
                        <input type="date" class="form-control" value="">
                    </div>
                </div>

                <div class="col-sm-3">
                    <label for="select" style="">Subject</label>
                    <select id="subjects_" class="form-control" name="" id="">
                        <option value="-1">All</option>
                    </select>
                </div>

                <div class="col-sm-3">
                    <label for="select" style="">Category</label>
                    <select id="categories_" class="form-control" name="" id="">
                        <option value="-1">All</option>
                    </select>
                </div>

            </div>
            <button class="btn btn-info">Search</button>
        </div>

        <div class="row" style="padding-top: 20px">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Office</th>
                        <th>Age</th>
                        <th>Start date</th>
                        <th>Salary</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tiger Nixon</td>
                        <td>System Architect</td>
                        <td>Edinburgh</td>
                        <td>61</td>
                        <td>2011-04-25</td>
                        <td>$320,800</td>
                    </tr>
                    <tr>
                        <td>Garrett Winters</td>
                        <td>Accountant</td>
                        <td>Tokyo</td>
                        <td>63</td>
                        <td>2011-07-25</td>
                        <td>$170,750</td>
                    </tr>
                    <tr>
                        <td>Ashton Cox</td>
                        <td>Junior Technical Author</td>
                        <td>San Francisco</td>
                        <td>66</td>
                        <td>2009-01-12</td>
                        <td>$86,000</td>
                    </tr>
                    <tr>
                        <td>Cedric Kelly</td>
                        <td>Senior Javascript Developer</td>
                        <td>Edinburgh</td>
                        <td>22</td>
                        <td>2012-03-29</td>
                        <td>$433,060</td>
                    </tr>
                    <tr>
                        <td>Airi Satou</td>
                        <td>Accountant</td>
                        <td>Tokyo</td>
                        <td>33</td>
                        <td>2008-11-28</td>
                        <td>$162,700</td>
                    </tr>
                    <tr>
                        <td>Brielle Williamson</td>
                        <td>Integration Specialist</td>
                        <td>New York</td>
                        <td>61</td>
                        <td>2012-12-02</td>
                        <td>$372,000</td>
                    </tr>
                    <tr>
                        <td>Herrod Chandler</td>
                        <td>Sales Assistant</td>
                        <td>San Francisco</td>
                        <td>59</td>
                        <td>2012-08-06</td>
                        <td>$137,500</td>
                    </tr>
                    <tr>
                        <td>Rhona Davidson</td>
                        <td>Integration Specialist</td>
                        <td>Tokyo</td>
                        <td>55</td>
                        <td>2010-10-14</td>
                        <td>$327,900</td>
                    </tr>
                    <tr>
                        <td>Colleen Hurst</td>
                        <td>Javascript Developer</td>
                        <td>San Francisco</td>
                        <td>39</td>
                        <td>2009-09-15</td>
                        <td>$205,500</td>
                    </tr>
                    <tr>
                        <td>Sonya Frost</td>
                        <td>Software Engineer</td>
                        <td>Edinburgh</td>
                        <td>23</td>
                        <td>2008-12-13</td>
                        <td>$103,600</td>
                    </tr>
                    <tr>
                        <td>Jena Gaines</td>
                        <td>Office Manager</td>
                        <td>London</td>
                        <td>30</td>
                        <td>2008-12-19</td>
                        <td>$90,560</td>
                    </tr>
                    <tr>
                        <td>Quinn Flynn</td>
                        <td>Support Lead</td>
                        <td>Edinburgh</td>
                        <td>22</td>
                        <td>2013-03-03</td>
                        <td>$342,000</td>
                    </tr>
                    <tr>
                        <td>Charde Marshall</td>
                        <td>Regional Director</td>
                        <td>San Francisco</td>
                        <td>36</td>
                        <td>2008-10-16</td>
                        <td>$470,600</td>
                    </tr>
                    <tr>
                        <td>Haley Kennedy</td>
                        <td>Senior Marketing Designer</td>
                        <td>London</td>
                        <td>43</td>
                        <td>2012-12-18</td>
                        <td>$313,500</td>
                    </tr>
                    <tr>
                        <td>Tatyana Fitzpatrick</td>
                        <td>Regional Director</td>
                        <td>London</td>
                        <td>19</td>
                        <td>2010-03-17</td>
                        <td>$385,750</td>
                    </tr>
                    <tr>
                        <td>Michael Silva</td>
                        <td>Marketing Designer</td>
                        <td>London</td>
                        <td>66</td>
                        <td>2012-11-27</td>
                        <td>$198,500</td>
                    </tr>
                    <tr>
                        <td>Paul Byrd</td>
                        <td>Chief Financial Officer (CFO)</td>
                        <td>New York</td>
                        <td>64</td>
                        <td>2010-06-09</td>
                        <td>$725,000</td>
                    </tr>
                    <tr>
                        <td>Gloria Little</td>
                        <td>Systems Administrator</td>
                        <td>New York</td>
                        <td>59</td>
                        <td>2009-04-10</td>
                        <td>$237,500</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Office</th>
                        <th>Age</th>
                        <th>Start date</th>
                        <th>Salary</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
@endsection

@section('javascript')
    
    <script src="{{ asset('/js/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/js/datatable/dataTables.bootstrap.min.js') }}"></script>

    <script src="{{ asset('/js/select2/select2.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            new DataTable('#example');
        });
    </script>

    <!-- Script -->
   <script type="text/javascript">
   // CSRF Token
   var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
   $(document).ready(function(){

    setDate()
    var default_option = [{"id": -1, "text": "All"}];

     $( "#authors_" ).select2({
        ajax: { 
          url: "{{route('getUsers')}}",
          type: "post",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
               _token: CSRF_TOKEN,
               search: params.term // search term
            };
          },
          processResults: function (response) {
            response = default_option.concat(response)
            return {
              results: response
            };
          },
          cache: true
        }

     });


     $( "#subjects_" ).select2({
        ajax: { 
          url: "{{route('getSubjects')}}",
          type: "post",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
               _token: CSRF_TOKEN,
               search: params.term // search term
            };
          },
          processResults: function (response) {
            response = default_option.concat(response)
            return {
              results: response
            };
          },
          cache: true
        }

     });


     $( "#categories_" ).select2({
        ajax: { 
          url: "{{route('getCategories')}}",
          type: "post",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
               _token: CSRF_TOKEN,
               search: params.term // search term
            };
          },
          processResults: function (response) {
            response = default_option.concat(response)
            return {
              results: response
            };
          },
          cache: true
        }

     });

   });

   function setDate(){
    const today = new Date();
    const yyyy = today.getFullYear();
    let mm = today.getMonth() + 1; // Months start at 0!
    let dd = today.getDate();

    if (dd < 10) dd = '0' + dd;
    if (mm < 10) mm = '0' + mm;

    const formattedToday = yyyy + '-' + mm + '-' +  dd ;
    $('input[type=date]').val(formattedToday);
   }
   </script>
@endsection