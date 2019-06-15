<?php
require_once ('./lib/conn_db.php');

$sql = "SELECT * FROM DRIVERS  ";
$result = mysqli_query($conMy, $sql);
while ($row = mysqli_fetch_assoc($result)){
    $drivers[] = $row;
}

$sql = "SELECT * FROM TRAILERS  ";
$result = mysqli_query($conMy, $sql);
$maxWeigth = 0;
while ($row = mysqli_fetch_assoc($result)){
    $trailers[] = $row;
    $maxWeigth = ($row['weigth_capacity'] > $maxWeigth) ? $row['weigth_capacity'] : $maxWeigth;
}

?>
<div class="page-header">
    <div class="col-md-12 ">
    <h1>Customer Loads</h1>
    <h4>Select loads from the table below and click "Next" to create a new Journey</h4>
    </div>
</div>
<div class="col-md-12">
<form class="form-inline" action=".">
    <input type="hidden" name="page" value="create_journey" />
    <input type="hidden" name="loadids" id="loadids" />
    <input type="hidden" id="maxweigth" value=<?echo $maxWeigth?> />
    
  
  <div class="form-group mx-sm-4 mb-2">
    <label for="weightAvailable">Total Weigth</label>&nbsp;&nbsp;&nbsp;
    <input disabled type="text" class="form-control" id="weightAvailable" name="weigth">
    <div class="invalid-feedback">
        Total Weigth exceeded the maximum capacity of all trailers
    </div>
  </div>
  <div class="form-group mx-sm-4 mb-2">
    <button type="submit" class="btn btn-primary mb-2" id="btnsubmit">Next</button>
  </div>
</form>
</div>


<div class="col-sm-12">

        <table class="table" id="table"
               data-toolbar="#toolbar"
               data-search="true"
               data-show-refresh="true"
               data-show-toggle="true"
               data-show-columns="true"
               data-url="src/list_customer_loads.php"
               data-show-export="true"
               data-search-on-enter-key="true"
               data-toggle="table"
               data-detail-view="false"
               data-sort-name="load_id"
               data-sort-order="desc"
               data-minimum-count-columns="2"
               data-show-pagination-switch="true"
               data-pagination="true"
               data-id-field="id"
               data-page-size="10"
               data-page-list="[5, 10, 25, 50, 100, ALL]"
               data-show-footer="false"
               data-side-pagination="server"
               data-icons-prefix="fa"       
               data-icons="icons"
               >
            
        </table>
    </div>
</div>
<script>
    var $table = $('#table');
    var $remove = $('#remove')
    var selections = []
    window.icons = {
        refresh: 'fa-sync-alt',
        toggleOn: 'fa-toggle-on',
        toggleOff: 'fa-toggle-off',
        columns: 'fa-columns',
        paginationSwitchDown: 'fa-chevron-circle-right',
        paginationSwitchUp: 'fa-chevron-right',
        detailOpen: 'fa-plus',
        detailClose: 'fa-minus'
    };
    var selections = [];
    var weigthSelections = [];
    

  function getIdSelections() {
    return $.map($table.bootstrapTable('getSelections'), function (row) {
      return row.id;
    })
  }

  function getWeigthSelections() {
    return $.map($table.bootstrapTable('getSelections'), function (row) {
      return row.WEIGTH;
    })
  }

  function sumReducer(total, num) {
  return Number(total) + Number(num);
}

  
    $(function () {

        $('#trailerselect').change(function(){
            var trailerId = $(this).val();
            $.ajax({
            type: "POST",
            url: "src/get_trailer_capacity.php",
            
            data: {trailer_id: trailerId}
            }).done(function (msg) {
                $('#weightAvailable').val(msg)
            });
        })

        $table.bootstrapTable('destroy').bootstrapTable({
            columns: [
                {field: 'state',
                 checkbox: true   
                }, {
                    title: 'Id',
                    field: 'id',
                    sortable: true
                }, {
                    title: 'Customer Name',
                    field: 'CUSTOMER_NAME',
                    sortable: true
                }, {
                    title: 'Origin',
                    field: 'ORIGIN',
                    sortable: true
                }, {
                    title: 'Destination',
                    field: 'DESTINATION',
                    sortable: true
                }, {
                    title: 'Distance',
                    field: 'DISTANCE',
                    sortable: true
                }, {
                    title: 'Weigth',
                    field: 'WEIGTH',
                    sortable: true
                }
                
            ]
        })

        $('#frm-search').on('submit', function(e){
            e.preventDefault();
        });


        $('#btn-search').on('click', function () {
            $table.bootstrapTable('destroy').bootstrapTable({
                queryParams: queryParams,
                method: 'get'
            });

        });

        $table.on('check.bs.table uncheck.bs.table ' +
            'check-all.bs.table uncheck-all.bs.table',
        function () {
        $remove.prop('disabled', !$table.bootstrapTable('getSelections').length)
      
            selections = getIdSelections();
            $("#loadids").val(selections);
            weigthSelections = getWeigthSelections();
            var totalWeigth = weigthSelections.reduce(sumReducer);
            
            $('#weightAvailable').val(totalWeigth);
            var maxWeigth = Number($('#maxweigth').val());
            if (totalWeigth > maxWeigth){
                $('#weightAvailable').addClass('is-invalid');
                $('#btnsubmit').attr('disabled', true);
                $('#btnsubmit').hide();
            } else {
                $('#weightAvailable').removeClass('is-invalid');
                $('#btnsubmit').attr('disabled', false);
                $('#btnsubmit').show();

            }
        
        })
        
    });
    
</script>
