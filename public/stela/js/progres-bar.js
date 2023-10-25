$(document).ready(function () {

  // hitung jumlah chckbox
  var count = 0;
  var checked = 0;

  function countBoxes() {
    count = $(".checkbox-bar").length;
    if (count == 0) {
      count = 1;
    }
  }

  countBoxes();
  $(".checkbox-bar").click(countBoxes());
  // console.log(count)

  // jumlah checklist

  function countChecked() {
    checked = $(".checkbox-bar:checked").length;

    // console.log('checked :'+checked)

    var percentage = parseInt(((checked / count) * 100), 10);
    $(".progressbar-bar").progressbar({
      value: percentage
    });
    $(".progressbar-label").text(percentage + "%");
  }

  countChecked();
  $(".checkbox-bar").click(countChecked);

  // ================================================================================
  for (let i = 0; i < statuss; i++) {
    $(".thp").eq(i).removeClass("non-aktif")
  }

  // let id_simpanan = 
  updateHalaman(id_simpanan);
  $('.thp').click(function () {
    updateHalaman($(this).data("id"));
  });

  function updateHalaman(simpan) {
    id_simpanan = simpan;
    // console.log(id_simpanan)
    switch (id_simpanan) {
      case 1:
        $('.nama-tahap').text('Permohonan');
        $('.thp-permohonan').css('display', 'block');
        $('.thp-all').css('display', 'none');
        break;
      case 2:
        $('.nama-tahap').text('Perencanaan');
        $('.thp-permohonan').css('display', 'none');
        $('.thp-all').css('display', 'block');
        break;
      case 3:
        $('.nama-tahap').text('Perancangan');
        $('.thp-permohonan').css('display', 'none');
        $('.thp-all').css('display', 'block');
        break;
      case 4:
        $('.nama-tahap').text('Implementasi');
        $('.thp-permohonan').css('display', 'none');
        $('.thp-all').css('display', 'block');
        break;
      case 5:
        $('.nama-tahap').text('Pengujian');
        $('.thp-permohonan').css('display', 'none');
        $('.thp-all').css('display', 'block');
        break;
      case 6:
        $('.nama-tahap').text('Serah Terima');
        $('.thp-permohonan').css('display', 'none');
        $('.thp-all').css('display', 'block');
        break;
    }


    $('.accordion').html('');

    Object.filter = (obj, predicate) =>
      Object.keys(obj)
      .filter(key => predicate(obj[key]))
      .reduce((res, key) => (res[key] = obj[key], res), {});

    let datas = Object.filter(detail, details => details['id_tahapan'] == id_simpanan);

    // console.log(datas)
    // console.log("=========================================")

    Object.values(Object.values(datas)).forEach((i, index) => {
      // console.log(i)
      // console.log("=========================================")

      $('.accordion').append(`
        <div class="accordion-item mb-2">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button fw-bold semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#id-sub-tahapan-${index+1}" aria-expanded="true" aria-controls="id-sub-tahapan-${i+1}">
                    <input class="all checkbox" type="checkbox" id="option" disabled ${i['status'] == 0? 'checked' : ''}/>
                    <div class="row mx-2 ">
                        <h4 class="fw-bold">${i['sub_tahapan']}</h4>
                </button>
            </h2>

            <div id="id-sub-tahapan-${index+1}" class="accordion-collapse collapse">
                <div class="accordion-body">
      `);

      if (i['todo_list'] != null) {
        Object.values(i['todo_list']).forEach((y) => {
          // console.log(y)
          // console.log(ind)
          $(`.accordion .accordion-item:nth-child(${index+1}) .accordion-body`).append(`
          <li class="list-group-item px-4">
              <label class="form-label row" for="email">
                  <input class="subOption checkbox col-md-2 col-sm-2 checkbox-bar" type="checkbox" disabled ${y['status'] == 0? 'checked' : ''}/>
                  <h6 class="col-md-10 col-sm-10 simpan-${y['id_todo_list']}">
                      <div class="fw-bold">${y['todo_list']}</div>
                      Nama : ${y['nama_programmer']}
                      <br>
                      Tanggal : ${y['tanggal']}
        `)
          if (y['dokumen'] != null) {
            $(`.accordion .accordion-item:nth-child(${index+1}) .accordion-body .simpan-${y['id_todo_list']}`).append(`
            <br>
            Dokumen : <a target="_blank" href="/storage/index/todo-list-dokumen/${y['dokumen']['doc_name']}/ext/${y['dokumen']['ext']}" ><i class="fa-regular fa-file fs-4 ms-2"></i></a>  
        `)
          }
          $(`.accordion .accordion-item:nth-child(${index+1}) .accordion-body .simpan-${y['id_todo_list']}`).append(`
                      <br>
                      Keterangan : ${y['keterangan']}
                  </h6>
              </label>
          </li>
          `)
        });
      }

      $('.accordion').append(`
              </div>
          </div>
      </div>
      `)

    });
    countBoxes();

    countChecked();
  };


});