<?
require_once './lib/conn_db.php';

$loadIds = $_GET['loadids'];
$loadWeigth = $_GET['weigth'];

$sql = "SELECT * FROM DRIVERS  ";
$result = mysqli_query($conMy, $sql);
while ($row = mysqli_fetch_assoc($result)){
    $drivers[] = $row;
}

$sql = "SELECT  WEIGTH_CAPACITY FROM TRAILERS WHERE TRAILER_ID = ?";

$stmt = mysqli_prepare($conMy, $sql);
mysqli_stmt_bind_param($stmt, "s", $trailerId);
 mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$weigthCapacity = '0';
while ($row = mysqli_fetch_assoc($result)){
    $weigthCapacity = $row['WEIGTH_CAPACITY'];
}


?>
<div class="col-md-12">
<h1>Create Journey</h1>
</div>
<div class="col-md-12">
<form class="form-inline" action=".">
    <input type="hidden" name="page" value="journey_created" />
    <input type="hidden" name="trailerid" id="trailerid" />
    <input type="hidden" name="loadids" value="<?echo $_GET['loadids']?>" />
    
  <div class="form-group mx-sm-3 mb-2">
    <label for="driverSelect">Driver</label>&nbsp;&nbsp;&nbsp;
    <select class="form-control" id="driverselect" name="driver">
        <option></option>
        <?
        foreach($drivers as $driver){
            echo "<option value='$driver[driver_id]'>$driver[driver_name]</option>";
        }
        ?>
    </select>
  </div>
  <button type="submit" disabled class="btn btn-primary mb-2" id="btnsubmit">Submit</button>
</form>
</div>


<div class="col-sm-12">

        <table class="table" id="table"
               data-toolbar="#toolbar"
               data-search="true"
               data-show-refresh="true"
               data-show-toggle="true"
               data-show-columns="true"
               data-url="src/list_trailers.php?weigth_capacity=<?echo $loadWeigth?>"
               data-single-select="true"
               data-show-export="true"
               data-search-on-enter-key="true"
               data-toggle="table"
               data-detail-view="false"
               data-sort-name="trailer_id"
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

        

        $table.bootstrapTable('destroy').bootstrapTable({
            columns: [
                {field: 'state',
                 checkbox: true   
                }, {
                    title: 'Id',
                    field: 'id',
                    sortable: true
                }, {
                    title: 'Trailer Name',
                    field: 'TRAILER_NAME',
                    sortable: true
                }, {
                    title: 'Capacity',
                    field: 'WEIGTH_CAPACITY',
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

        $('#driverselect').change(function() {
            var value =  $(this).val();
            if (value.length > 0 && selections.length>0){
                $('#btnsubmit').attr('disabled', false);
            } else {
                $('#btnsubmit').attr('disabled', true);
            }
        })

        $table.on('check.bs.table uncheck.bs.table ' +
            'check-all.bs.table uncheck-all.bs.table',
        function () {
            $remove.prop('disabled', !$table.bootstrapTable('getSelections').length)
            selections = getIdSelections();
            $("#trailerid").val(selections);
            if (selections.length>0 && $('#driverselect').val().length > 0) {
                $('#btnsubmit').attr('disabled', false);
            } else {
                $('#btnsubmit').attr('disabled', true);
            }
            
        
        })
        
    });


    function queryParams(params) {
        params.id_viagem = $('#in-id-manifesto').val();
        params.num_manifesto = $('#in-num-manifesto').val();
        params.dt_viagem = $('#in-data-viagem').val();
        params.dt_proc = $('#in-data-proc').val();
        params.cidade = $('#in-cidade').val();
        params.tp_veiculo = $('#sel-tp-veiculo').val();
        params.ws_enviado = $('#sel-ws').val();
        return params;
    }

    function ajaxRequest(params) {
        $.ajax({
            type: "POST",
            url: "lib/list_manifestos.php",
            dataType: "json",
            data: params.data
        }).done(function (msg) {

            params.success({
                total: 100,
                rows: msg
            });
        });
    }

    function sendWs(idviagem, tpveiculo, idmanifesto, elem) {
        if (tpveiculo === '' || typeof tpveiculo === 'undefined') {
            alert('Tipo de Veículo não definido. Dados não serão enviados');
            return false;
        }



        var htmlStr = '';
        $.ajax({
            type: "POST",
            url: "lib/send_single_ws.php",
            data: {idviagem: idviagem},
            //async: false,
            beforeSend: function () {
                htmlStr = $('#btnlist' + idviagem).text();
                $(elem).prop("disabled", true);
                $(elem).html('Enviando...');
            }
        }).done(function (msg) {
            if (msg !== 'OK') {
                alert(msg);
            } else {
                window.location.reload();
            }
        });

    }

    




</script>
