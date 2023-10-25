$(document).ready(function () {
    (function () {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()

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
    if (sub != null) {
        Object.values(Object.values(sub)).forEach((i, index) => {
            $('.tasks').append(`
            <div class="mb-2 d-flex align-items-center">
                <a class="btn push1 btn-primary ms-2" data-id="${i['id_sub_tahapan']}" data-bs-toggle="modal" data-bs-target="#tambah-todo">Tambah Todo List</a>
            </div>
            <div class="accordion-item mb-2">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-bold semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#id-sub-tahapan-${index+1}" aria-expanded="true" aria-controls="id-sub-tahapan-1">
                        <input class="all checkbox" type="checkbox" id="option" disabled ${i['status'] == 0? 'checked' : ''}/>
                        <h4 class="fw-bold ms-2">${i['sub_tahapan']}</h4>
                        <a class="ms-2 delete bg-danger p-1" type="button" data-id="${i['id_sub_tahapan']}" style="border-radius:3px" data-bs-toggle="modal" data-bs-target="#delete">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                        <a class="ms-2 edit bg-primary p-1" type="button" data-nama="${i['sub_tahapan']}" data-id="${i['id_sub_tahapan']}" style="border-radius:3px" data-bs-toggle="modal" data-bs-target="#edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </button>
                </h2>

                <div id="id-sub-tahapan-${index+1}" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        
                    </div>
                </div>
            </div>
        `);
            if (i['todo_list'] != null) {
                Object.values(i['todo_list']).forEach((y, test) => {
                    $(`#id-sub-tahapan-${index+1} .accordion-body`).append(`
                        <li class="list-group-item p-0">
                        <label class="form-label row" for="email">
                            <input class="subOption checkbox col-md-2 col-sm-2 checkbox-bar" type="checkbox" disabled ${y['status'] == 0? 'checked' : ''}/>
                            <h6 class="col-md-7 col-sm-7 status-todo-${index+1}-${test+1}">
                            </h6>
                            <div class="col-md-2 d-flex justify-content-center align-items-center">
                            <a class="m-2 delete-todo bg-danger p-1" type="button" data-id="${y['id_todo_list']}" style="border-radius:3px" data-bs-toggle="modal" data-bs-target="#delete-todo">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                            <a class="m-2 edit-todo bg-primary p-1" type="button" data-nama="${y['todo_list']}" data-id="${y['id_todo_list']}" style="border-radius:3px" data-bs-toggle="modal" data-bs-target="#edit-todo">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a class="m-2 revisi bg-warning p-1" type="button" data-id="${y['id_todo_list']}" style="border-radius:3px;color:white !important;" data-bs-toggle="modal" data-bs-target="#revisi">
                                Revisi
                            </a>
                            <a class="m-2 laporan-${index+1}-${test+1} laporan bg-success p-1" type="button" data-id="${y['id_todo_list']}" style="border-radius:3px;color:white !important;" data-bs-toggle="modal" data-bs-target="#laporan">
                                Laporan
                            </a>
                            </div>
                        </label>
                        </li>
                    `)
                    if (y['status'] == 0) {
                        console.log(y);
                        $(`#id-sub-tahapan-${index+1} .accordion-body .status-todo-${index+1}-${test+1}`).append(`
                            <div class="fw-bold">${y['todo_list']}</div>
                            Nama : ${y['nama_programmer']}
                            <br>
                            Tanggal : ${y['tanggal']}
                            <br>
                            <div class="dokumen-todo-${index+1}-${test+1}">                  
                            </div>
                            Keterangan : ${y['keterangan']}
                        `)
                        $(`.laporan-${index+1}-${test+1}`).addClass("d-none");
                    } else if (y['status'] == 1) {
                        $(`#id-sub-tahapan-${index+1} .accordion-body .status-todo-${index+1}-${test+1}`).append(`
                            <div class="fw-bold">${y['todo_list']}</div>
                            Nama : -
                            <br>
                            Tanggal : -
                            <br>
                            Keterangan : -
                        `)
                    } else if (y['status'] == 2) {
                        $(`#id-sub-tahapan-${index+1} .accordion-body .status-todo-${index+1}-${test+1}`).append(`
                            <div class="fw-bold">${y['todo_list']}</div>
                            Keterangan Revisi : ${y['keterangan_revisi']}
                            <br>
                        `)
                    }

                    if (y['dokumen'] != null) {
                        $(`.dokumen-todo-${index+1}-${test+1}`).append(`
                        Dokumen : <a target="_blank" href="/storage/index/todo-list-dokumen/${y['dokumen']['doc_name']}/ext/${y['dokumen']['ext']}" ><i class="fa-regular fa-file fs-4 ms-2"></i></a>
                    `)
                    }
                });
            }
            countBoxes();

            countChecked();

        });
    }

    if (simpan == "programmer") {
        $(".push1, .push, .delete, .edit, .delete-todo, .edit-todo, .revisi").addClass("d-none");
    }

    function dashboard(i) {
        $('.isi-internal').html(`
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
            <div class="inner">
                <h3>${list[i]['internal']['permohonan']}</h3>
                <p>Permohonan</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="/programmer/detail/developer/internal/tahapan/permohonan/tahun/${i}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
            <div class="inner">
                <h3>${list[i]['internal']['perencanaan']}</h3>
                <p>Perencanaan</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="/programmer/detail/developer/internal/tahapan/perencanaan/tahun/${i}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
            <div class="inner text-white">
                <h3>${list[i]['internal']['perancangan']}</h3>
                <p>Perancangan</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="/programmer/detail/developer/internal/tahapan/perancangan/tahun/${i}" class="small-box-footer" style="color:white !important;">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
            <div class="inner">
                <h3>${list[i]['internal']['implementasi']}</h3>
                <p>Implementasi</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="/programmer/detail/developer/internal/tahapan/implementasi/tahun/${i}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box" style="background:#666699">
            <div class="inner text-white">
                <h3>${list[i]['internal']['pengujian']}</h3>
                <p>Pengujian</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="/programmer/detail/developer/internal/tahapan/pengujian/tahun/${i}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box" style="background:#ff00ff">
            <div class="inner text-white">
                <h3>${list[i]['internal']['serah terima']}</h3>
                <p>Serah Terima</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="/programmer/detail/developer/internal/tahapan/serah terima/tahun/${i}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        </div>
        `)

        $('.isi-eksternal').html(`
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
            <div class="inner">
                <h3>${list[i]['external']['permohonan']}</h3>
                <p>Permohonan</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="/programmer/detail/developer/external/tahapan/permohonan/tahun/${i}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
            <div class="inner">
                <h3>${list[i]['external']['perencanaan']}</h3>
                <p>Perencanaan</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="/programmer/detail/developer/external/tahapan/perencanaan/tahun/${i}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
            <div class="inner text-white">
                <h3>${list[i]['external']['perancangan']}</h3>
                <p>Perancangan</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="/programmer/detail/developer/external/tahapan/perancangan/tahun/${i}" class="small-box-footer" style="color:white !important;">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
            <div class="inner">
                <h3>${list[i]['external']['implementasi']}</h3>
                <p>Implementasi</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="/programmer/detail/developer/external/tahapan/implementasi/tahun/${i}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box" style="background:#666699">
            <div class="inner text-white">
                <h3>${list[i]['external']['pengujian']}</h3>
                <p>Pengujian</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="/programmer/detail/developer/external/tahapan/pengujian/tahun/${i}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box" style="background:#ff00ff">
            <div class="inner text-white">
                <h3>${list[i]['external']['serah terima']}</h3>
                <p>Serah Terima</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="/programmer/detail/developer/external/tahapan/serah terima/tahun/${i}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        </div>
        `)
    }

    


    $('body').on('click', '.push1', function () {
        $(".modal-body #id_sub").val($(this).data('id'));
    });
    $('body').on('click', '.delete', function () {
        $(".modal-body #id_sub").val($(this).data('id'));
    });
    $('body').on('click', '.delete-todo', function () {
        $(".modal-body #id_todo").val($(this).data('id'));
    });
    $('body').on('click', '.edit', function () {
        $(".modal-body #sub").val($(this).data('nama'));
        $(".modal-body #id_sub").val($(this).data('id'));
    });
    $('body').on('click', '.edit-todo', function () {
        $(".modal-body #todo").val($(this).data('nama'));
        $(".modal-body #id-todo").val($(this).data('id'));
    });
    $('body').on('click', '.revisi', function () {
        $(".modal-body #id_todo").val($(this).data('id'));
    });
    $('body').on('click', '.laporan', function () {
        $(".modal-body #id_todo").val($(this).data('id'));
    });
    $('body').on('click', '.delete-app', function () {
        $(".modal-body #id").val($(this).data('id'));
    });

    $('body').on('change', '#tahun-pilih', function () {
        dashboard($(this).val());
    });

    if (list != null) {
        dashboard('All');
    }
    
});