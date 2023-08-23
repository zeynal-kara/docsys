@extends("voyager::master")

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/datatable/jquery.dataTables.min.css') }}">
    <style>
        .prevent-select {
            -webkit-user-select: none; /* Safari */
            -ms-user-select: none; /* IE 10 and IE 11 */
            user-select: none; /* Standard syntax */
            margin-bottom: 8px;
            margin-top: 50px;
            display: flex;
            align-items: flex-start;
        }
    </style>
@endsection

@section('content')

<div class="container">
        <div class="row" style="padding: 10px">
            <div class="col-12 prevent-select">
                <input v-model="s_in_name" id="search_in_name" type="checkbox"  style="margin-right:5px" checked>
                <label for="search_in_name">Search In File Name</label>

                <input v-model="s_in_content" id="search_in_content" type="checkbox" style="margin-left: 20px; margin-right:5px">
                <label for="search_in_content">Search In Content <strong>[Advanced Search]</strong> </label>
            </div>

            <div v-show="s_in_name" class="col-12">
                <input id="name_search_term" type="text" class="form-control" placeholder="File Name .." autocomplete="off">
            </div>

            <div v-show="s_in_content" class="col-12">
                <input id="content_search_term" type="text" class="form-control" placeholder="Content..." autocomplete="off">
            </div>

            <div class="row justify-content-center">
                <div class="col-sm-3">
                    <label for="select" style="">Author</label>
                    <select id="authors_" class="form-control" name="" id="">
                        <option value="-1">All</option>
                    </select>
                </div>

                <div class="col-sm-3">
                    <label for="select" style="">Subject</label>
                    <select id="subjects_" class="form-control" name="" id="">
                        <option value="-1">All</option>
                    </select>
                </div>

                <div class="col-sm-2">
                    <label for="select" style="">Category</label>
                    <select id="categories_" class="form-control" name="" id="">
                        <option value="-1">All</option>
                    </select>
                </div>

                <div class="col-sm-4">
                    <label for="datepicker" style="">File Date</label>
                    <div class="row" style="display: flex; margin:0">
                        <input id="file_date_start" type="date" class="form-control" value="">
                        <div class="input-group-addon" style="width:50px">to</div>
                        <input id="file_date_end" type="date" class="form-control" value="">
                    </div>
                </div>

            </div>
            <button id="search_btn" class="btn btn-info">Search</button>
        </div>

        <div class="row" style="padding-top: 20px">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>File</th>
                        <th>Name</th>
                        <th>Desc</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Author</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>File</th>
                        <th>Name</th>
                        <th>Desc</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Author</th>
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
    <script src="{{ asset('/js/vuejs/vue.global.js') }}"></script>

    <script>
        const app = Vue.createApp({
           data(){
                return{
                    s_in_name: false,
                    s_in_content: false,
                }
           }
        }).mount(".container");
    </script>

    <script>

        var table;

        $(document).ready(function () {
            table = $("#example").DataTable({
                processing:true,
                paging:true,
                ajax: {
                    url: "{{route('getFilteredDoc')}}",
                    method: "POST",
                    dataType: "json",
                    "data": function ( d ) {
                        getFilterOptions(d);
                    }
                },
                columns:[
                    {"data":"id"},
                    {"data":"_media.original_url"},
                    {"data":"name"},
                    {"data":"desc"},
                    {"data":"category.name"},
                    {"data":"date"},
                    {"data":"author.name"},
                ],
                "columnDefs": [ {
                    "targets": 1,
                    "data": "_media.original_url",
                    "render": function ( data, type, row, meta ) {
                        return '<a href="'+data+'" target="_blank"><img class="file-type" src="http://127.0.0.1:8000/admin/voyager-extension-assets?path=icons%2Ffiles%2Fpdf.svg" style="height: 25px; width:auto"></a>';
                    }
                }]
            });
        });

        $('#content_search_term').keypress(function (e) {
            var key = e.which;
            if(key == 13)  // the enter key code
            {
                $("#search_btn").click();
            }
        });

        $('#name_search_term').keypress(function (e) {
            var key = e.which;
            if(key == 13)  // the enter key code
            {
                $("#search_btn").click();
            }
        });


        $("#search_btn").click(function(){
            table.ajax.reload();
        });

        function getFilterOptions(data) {
            data.category_id = $("#categories_").val();
            data.subject_id = $("#subjects_").val();
            data.author_id = $("#authors_").val();
            data.file_date_start = $("#file_date_start").val();
            data.file_date_end = $("#file_date_end").val();

            data.content_search_term = 
                $("#search_in_content").is(':checked') ? $("#content_search_term").val().trim() : "";
            data.name_search_term = 
                $("#search_in_name").is(':checked') ? $("#name_search_term").val().trim() : "";
            
            return data;
        }

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
