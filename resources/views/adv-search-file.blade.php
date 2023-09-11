@extends("voyager::master")

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/datatable/jquery.dataTables.min.css') }}">
    <style>
        @font-face {
            font-family: "Roboto Slab";
            src: url('/font/static/RobotoSlab-SemiBold.ttf') format("truetype");
        }
        .prevent-select {
            -webkit-user-select: none; /* Safari */
            -ms-user-select: none; /* IE 10 and IE 11 */
            user-select: none; /* Standard syntax */
            margin-bottom: 8px;
            margin-top: 50px;
            display: flex;
            align-items: flex-start;
        }

        label{
            font-weight: bold;
            font-size: smaller !important;
        }

        mark{
            color: black;
            background-color: bisque;
            padding: 0
        }

        #example tr td{
            overflow: hidden;
            color: black;
            font-family: 'Roboto Slab', serif;
        }

        .search-option label{
            display: inline-flex;
            margin: 0 10px 0 10px;
        }
        
        .search-option label:first-child{
            margin: 0 10px 0 0;
        }

        .search-option label input{
            margin:0px !important;
            margin-right: 5px !important;
        }
    </style>
@endsection

@section('content')

<div class="container">
        <div class="row" style="padding: 10px; margin-top: 18px">
           
            <div class="col-12" style="margin-bottom: 12px">
                <label for="content_search_term">Content:</label>
                <textarea id="content_search_term" type="text" class="form-control" 
                placeholder="Content .." autocomplete="off"></textarea>
            </div>

            <div class="col-12 search-option">
                <label>
                    <input type="radio" name="opt" id="full_page_search" checked> Full Page Search
                </label>
                <label>
                    <input type="radio" name="opt"> Single Page Search
                </label>
            </div>

            <button id="search_btn" class="btn btn-info">Search</button>
        </div>

        <div class="row" style="padding-top: 20px">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Page ID</th>
                        <th>Page</th>
                        <th>Content</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                    <tr>
                        <th>Page ID</th>
                        <th>Page</th>
                        <th>Content</th>
                        <th>Score</th>
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

        var table;

        $(document).ready(function () {
            table = $("#example").DataTable({
                dom: 'plrti',
                searching: false,
                ordering: false,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{route('getAdvFilteredDoc')}}",
                    method: "POST",
                    dataType: "json",
                    "data": function ( d ) {
                        getFilterOptions(d);
                    }
                },
                columns:[
                    {"data":"_source.meta.raw.dsfile_id", "width": "10%"},
                    {"data":"_source.path.virtual", "width": "10%"},
                    {"data":"highlight.content", "width": "70%",},
                    {"data":"_score", "width": "10%"}
                    // {"hits":"_media.original_url"},
                    // {"hits":"name"},
                    // {"hits":"desc"},
                    // {"hits":"category.name"},
                    // {"hits":"date"},
                    // {"hits":"author.name"},
                ],
                "columnDefs": [ 
                    {
                        "targets": 1,
                        "render": function ( data, type, row, meta ) {
                            return '<a href="/files/doc_root'+data+'" target="_blank"><img class="file-type" src="/admin/voyager-extension-assets?path=icons%2Ffiles%2Fpdf.svg" style="height: 25px; width:auto"></a>';
                        }
                    },
                    {
                        "targets": 2,
                        "render": function ( data, type, row, meta ) {
                            return '<p class="mcontent">'+cleanString(data)+'</p>';
                        }
                    }
                ]
            });
        });

        $('#content_search_term, #name_search_term, #desc_search_term').keypress(function (e) {
            var key = e.which;
            if(key == 13)  // the enter key code
            {
                $("#search_btn").click();
                return false;
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

            // data.name_search_term = $("#name_search_term").val().trim();
            // data.desc_search_term = $("#desc_search_term").val().trim();
            data.content_search_term = $("#content_search_term").val().trim();
            data.search_type = $("#full_page_search").is(":checked") ? "orginal" : "part";
            
            return data;
        }

        //Function to remove ASCII characters
        function cleanString(input) {
            console.log(input);
            input = input.toString().replace(/�/g, "");
            // input = input.toString().replace(/markstrong/g, "<mark><strong>");
            // input = input.toString().replace(/strongmark/g, "</strong></mark>");
            return input;
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
