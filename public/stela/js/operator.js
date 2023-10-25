$(document).ready(function () {
    function dashboardOp(i) {
        $('.isi-permintaan').html(`
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                <h3>${listOp[i]['permintaan']}</h3>
                <p>Permintaan</p>
                </div>
                <div class="icon">
                <i class="ion ion-bag"></i>
                </div>
                <a href="/operator/permintaan/tahun/${listOp[i]['tahun']}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        `)
    }

    $('body').on('change', '#tahun-pilih-permintaan', function () {
        dashboardOp($(this).val());
    });

    if (listOp != null) {
        dashboardOp('All');
    }


});