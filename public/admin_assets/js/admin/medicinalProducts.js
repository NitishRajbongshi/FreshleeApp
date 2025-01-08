$(function () {
    $('#tblMdcn').DataTable({
        paging: true,
        ordering: true,
        info: true,
    })
        .buttons()
        .container()
        .appendTo(".mdcn_prod");
});


$('#editModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var modal = $(this);
    var techCode = button.data('tech-cd');
    var techName = button.data('tech-name');
    var tradeCode = button.data('trade-cd');
    var tradeName = button.data('trade-name');

    modal.find('#tech_code').val(techCode);
    modal.find('#tech_name').val(techName);
    modal.find('#trade_code').val(tradeCode);
    modal.find('#trade_name').val(tradeName);
    prd_type
    manufc_name

    trade_code

});