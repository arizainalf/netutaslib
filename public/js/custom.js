const datatableCall = (targetId, url, columns) => {
    $(`#${targetId}`).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: "GET",
            data: function (d) {
                d.mode = "datatable";
                d.tanggal_mulai = $("#tanggal_mulai").val() ?? null;
                d.tanggal_selesai = $("#tanggal_selesai").val() ?? null;
                d.bulan = $("#bulan_filter").val() ?? null;
                d.tahun = $("#tahun_filter").val() ?? null;
                d.tanggal = $("#tanggal_filter").val() ?? null;
            },
        },
        columns: columns,
        lengthMenu: [
            [25, 50, 100, 250, -1],
            [25, 50, 100, 250, "All"],
        ],
        // su
        columnDefs: [{ width: "5%", targets: 0 }],
    });
};

const ajaxCall = (url, method, data, successCallback, errorCallback) => {
    $.ajax({
        type: method,
        enctype: "multipart/form-data",
        url,
        cache: false,
        data,
        contentType: false,
        processData: false,
        headers: {
            Accept: "application/json",
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
        },
        dataType: "json",
        success: function (response) {
            successCallback(response);
        },
        error: function (error) {
            errorCallback(error);
        },
    });
};

const getModal = (targetId, url = null, fields = null) => {
    $(`#${targetId}`).modal("show");
    $(`#${targetId} .form-control`).removeClass("is-invalid");
    $(`#${targetId} .invalid-feedback`).html("");
    $(`#${targetId} small .text-danger`).html("");
    const cekLabelModal = $("#label-modal");
    if (cekLabelModal) {
        $("#id").val("");
        cekLabelModal.text("Tambah");
    }

    if (url) {
        cekLabelModal.text("Edit");
        const successCallback = function (response) {
            fields.forEach((field) => {
                if (response.data[field]) {
                    $(`#${targetId} #${field}`)
                        .val(response.data[field])
                        .trigger("change");
                }
            });
        };

        const errorCallback = function (error) {
            console.log(error);
        };
        ajaxCall(url, "GET", null, successCallback, errorCallback);
    }
    $(`#${targetId} .form-control`).val("");
};

const handleSuccess = (
    response,
    dataTableId = null,
    modalId = null,
    redirect = null
) => {
    if (dataTableId !== null) {
        swal({
            title: "Berhasil",
            icon: "success",
            text: response.message,
            timer: 2000,
            buttons: false,
        });
        $(`#${dataTableId}`).DataTable().ajax.reload();
    }

    if (modalId !== null) {
        $(`#${modalId}`).modal("hide");
    }

    if (redirect) {
        swal({
            title: "Berhasil",
            icon: "success",
            text: response.message,
            timer: 2000,
            buttons: false,
        }).then(function () {
            window.location.href = redirect;
        });
    }

    if (redirect == "no") {
        swal({
            title: "Berhasil",
            icon: "success",
            text: response.message,
            timer: 2000,
            buttons: false,
        });
    }
};

const handleValidationErrors = (error, formId = null, fields = null) => {
    if (error.responseJSON.data && fields) {
        fields.forEach((field) => {
            if (error.responseJSON.data[field]) {
                $(`#${formId} #${field}`).addClass("is-invalid");
                $(`#${formId} #error${field}`).html(
                    error.responseJSON.data[field][0]
                );
            } else {
                $(`#${formId} #${field}`).removeClass("is-invalid");
                $(`#${formId} #error${field}`).html("");
            }
        });
    } else {
        swal({
            title: "Gagal",
            icon: "error",
            text: error.responseJSON.message || error,
            timer: 2000,
            buttons: false,
        });
    }
};

const handleSimpleError = (error) => {
    swal({
        title: "Gagal",
        icon: "error",
        text: error,
        timer: 2000,
        buttons: false,
    });
};

const confirmDelete = (url, tableId) => {
    swal({
        title: "Apakah Kamu Yakin?",
        text: "ingin menghapus data ini!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            const data = null;

            const successCallback = function (response) {
                handleSuccess(response, tableId, null);
            };

            const errorCallback = function (error) {
                console.log(error);
            };

            ajaxCall(url, "DELETE", data, successCallback, errorCallback);
        }
    });
};
const confirmApprove = (url, tableId) => {
    swal({
        title: "Apakah Kamu Yakin?",
        text: "Konfirmasi Pengembalian Buku Ini?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willApprove) => {
        if (willApprove) {
            const data = null;

            const successCallback = function (response) {
                handleSuccess(response, tableId, null);
            };

            const errorCallback = function (error) {
                console.log(error);
            };

            ajaxCall(url, "GET", data, successCallback, errorCallback);
        }
    });
};

const setButtonLoadingState = (buttonSelector, isLoading, title = "Simpan") => {
    const buttonText = isLoading
        ? `<i class="fas fa-spinner fa-spin mr-1"></i> ${title}`
        : title;
    $(buttonSelector).prop("disabled", isLoading).html(buttonText);
};

const select2ToJson = (selector, url, modal = null, jenis = "null") => {
    const selectElem = $(selector);

    if (selectElem.children().length > 0) {
        return;
    }

    const successCallback = function (response) {
        const emptyOption = $("<option></option>");
        emptyOption.attr("value", "");
        emptyOption.text("-- Pilih Data --");
        selectElem.append(emptyOption);

        const responseList = response.data;
        responseList.forEach(function (row) {
            const option = $("<option></option>");
            option.attr("value", row.id);
            const label = row.nama ? row.nama : row.judul;
            option.text(label);
            selectElem.append(option);
        });

        selectElem.select2({});
    };

    const errorCallback = function (error) {
        console.log(error);
    };

    ajaxCall(url, "GET", null, successCallback, errorCallback);
};

const updateJam = () => {
    let jam = new Date();
    $("#jam").html(
        "Jam " +
            setUpJam(jam.getHours()) +
            ":" +
            setUpJam(jam.getMinutes()) +
            ":" +
            setUpJam(jam.getSeconds())
    );
};

const setUpJam = (jam) => {
    return jam < 10 ? "0" + jam : jam;
};

const togglePasswordVisibility = (inputSelector, iconSelector) => {
    let passwordInput = $(inputSelector);
    let toggleIcon = $(iconSelector);

    if (passwordInput.attr("type") === "password") {
        passwordInput.attr("type", "text");
        toggleIcon.removeClass("fas fa-eye").addClass("fas fa-eye-slash");
    } else {
        passwordInput.attr("type", "password");
        toggleIcon.removeClass("fas fa-eye-slash").addClass("fas fa-eye");
    }
};
const createChart = (labels, berkunjung, peminjaman, pengembalian) => {
    const statistics_chart = $("#myChart");

    if (statistics_chart.data("chart")) {
        statistics_chart.data("chart").destroy();
    }

    const ctx = statistics_chart[0].getContext("2d");

    const myChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Kunjungan",
                    data: berkunjung,
                    borderWidth: 5,
                    borderColor: "#47c363",
                    backgroundColor: "rgba(71, 195, 99, 0.3)",
                    pointBackgroundColor: "#fff",
                    pointBorderColor: "#47c363",
                    pointRadius: 4,
                },
                {
                    label: "Peminjaman Buku",
                    data: peminjaman,
                    borderWidth: 5,
                    borderColor: "#ffa426",
                    backgroundColor: "rgba(255, 164, 38, 0.3)",
                    pointBackgroundColor: "#fff",
                    pointBorderColor: "#ffa426",
                    pointRadius: 4,
                },
                {
                    label: "Pengembalian Buku",
                    data: pengembalian,
                    borderWidth: 5,
                    borderColor: "#fc544b",
                    backgroundColor: "rgba(252, 84, 75, 0.3)",
                    pointBackgroundColor: "#fff",
                    pointBorderColor: "#fc544b",
                    pointRadius: 4,
                },
            ],
        },
        options: {
            legend: {
                display: true,
            },
            scales: {
                yAxes: [
                    {
                        gridLines: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: 50,
                        },
                    },
                ],
                xAxes: [
                    {
                        gridLines: {
                            color: "#fbfbfb",
                            lineWidth: 2,
                        },
                    },
                ],
            },
        },
    });

    statistics_chart.data("chart", myChart);
};
