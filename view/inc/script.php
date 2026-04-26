
<!-- Bootstrap core JavaScript-->
<script src="<?= SERVERURL; ?>view/vendor/jquery/jquery.min.js"></script>
<script src="<?= SERVERURL; ?>view/js/bootstrap.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?= SERVERURL; ?>view/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?= SERVERURL; ?>view/js/SendForm.js"></script>
<script src="<?= SERVERURL; ?>view/js/sweetalert2.min.js"></script>

<script src="<?= SERVERURL; ?>view/js/jquery-3.6.0.min.js"></script>
<script src="<?= SERVERURL; ?>view/js/app.js"></script>
<!-- datatable js files -->
<script src="<?= SERVERURL; ?>view/js/jquery.dataTables.min.js"></script>
<script src="<?= SERVERURL; ?>view/js/datatables.min.js"></script>
<script src="<?= SERVERURL; ?>view/js/dataTables.bootstrap5.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        var t = $('#example').DataTable( { 
            language: {
                url: '<?= SERVERURL; ?>view/js/dataTables-Español.json'
            },
            lengthMenu: [[5, 10, 15, 20, 25, 50, 100, -1], [5, 10, 15, 20, 25, 50, 100, "Todos"]],
            responsive: true,
        } );

        t.on( 'order.dt search.dt', function () {
            let i = 1;
    
            t.cells(null, 0, {search:'applied', order:'applied'}).every( function (cell) {
                this.data(i++);
            } );
        } ).draw();
    } );
    
    function dataTable(classTable = "example"){
        var t = $(`.${classTable}`).DataTable( { 
            language: {
                url: '<?= SERVERURL; ?>view/js/dataTables-Español.json'
            },
            lengthMenu: [[5, 10, 15, 20, 25, 50, 100, -1], [5, 10, 15, 20, 25, 50, 100, "Todos"]],
            responsive: true,
        } );

        t.on( 'order.dt search.dt', function () {
            let i = 1;
            t.cells(null, 0, {search:'applied', order:'applied'}).every( function (cell) {
                this.data(i++);
            } );
        } ).draw();
    }
    
</script>
