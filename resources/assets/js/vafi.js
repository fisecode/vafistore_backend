'use strict';
let labelColor;

if (isDarkStyle) {
  labelColor = config.colors_dark.textMuted;
} else {
  labelColor = config.colors.textMuted;
}

const salesByDayChartEl = document.querySelector('#salesByDayChart'),
  salesByDayChartConfig = {
    chart: {
      height: 280,
      type: 'bar',
      parentHeightOffset: 0,
      toolbar: {
        show: false
      }
    },
    plotOptions: {
      bar: {
        borderRadius: 12,
        distributed: true,
        columnWidth: '55%',
        endingShape: 'rounded',
        startingShape: 'rounded'
      }
    },
    series: [
      {
        data: [38, 55, 48, 65, 80, 38, 48]
      }
    ],
    tooltip: {
      enabled: false
    },
    legend: {
      show: false
    },
    dataLabels: {
      enabled: false
    },
    colors: [
      config.colors_label.warning,
      config.colors.warning,
      config.colors_label.warning,
      config.colors.warning,
      config.colors.warning,
      config.colors_label.warning,
      config.colors_label.warning
    ],
    grid: {
      show: false,
      padding: {
        top: -15,
        left: -7,
        right: -4
      }
    },
    states: {
      hover: {
        filter: {
          type: 'none'
        }
      },
      active: {
        filter: {
          type: 'none'
        }
      }
    },
    xaxis: {
      axisTicks: {
        show: false
      },
      axisBorder: {
        show: false
      },
      categories: ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
      labels: {
        style: {
          colors: labelColor
        }
      }
    },
    yaxis: { show: false },
    responsive: [
      {
        breakpoint: 1200,
        options: {
          chart: {
            height: 266
          }
        }
      }
    ]
  };
if (typeof salesByDayChartEl !== undefined && salesByDayChartEl !== null) {
  const salesByDayChart = new ApexCharts(salesByDayChartEl, salesByDayChartConfig);
  salesByDayChart.render();
}

$(function () {
  'use strict';
  const dataSementara = [
    {
      tanggal: '2023-10-01',
      noTransaksi: '001',
      total: 100
    },
    {
      tanggal: '2023-10-02',
      noTransaksi: '002',
      total: 150
    },
    {
      tanggal: '2023-10-02',
      noTransaksi: '003',
      total: 150
    },
    {
      tanggal: '2023-10-02',
      noTransaksi: '004',
      total: 150
    },
    {
      tanggal: '2023-10-02',
      noTransaksi: '005',
      total: 150
    },
    {
      tanggal: '2023-10-02',
      noTransaksi: '006',
      total: 150
    },
    {
      tanggal: '2023-10-02',
      noTransaksi: '007',
      total: 150
    },
    {
      tanggal: '2023-10-02',
      noTransaksi: '008',
      total: 150
    },
    {
      tanggal: '2023-10-02',
      noTransaksi: '009',
      total: 150
    },
    {
      tanggal: '2023-10-02',
      noTransaksi: '010',
      total: 150
    },
    {
      tanggal: '2023-10-02',
      noTransaksi: '011',
      total: 150
    },
    {
      tanggal: '2023-10-03',
      noTransaksi: '012',
      total: 200
    }
  ];

  const dataSell = [
    {
      tanggal: '2023-10-01',
      noTransaksi: '001',
      total: 100
    },
    {
      tanggal: '2023-10-02',
      noTransaksi: '002',
      total: 150
    },
    {
      tanggal: '2023-10-02',
      noTransaksi: '003',
      total: 150
    },
    {
      tanggal: '2023-10-02',
      noTransaksi: '004',
      total: 150
    },
    {
      tanggal: '2023-10-02',
      noTransaksi: '005',
      total: 150
    },
    {
      tanggal: '2023-10-02',
      noTransaksi: '006',
      total: 150
    },
    {
      tanggal: '2023-10-02',
      noTransaksi: '007',
      total: 150
    },
    {
      tanggal: '2023-10-02',
      noTransaksi: '008',
      total: 150
    },
    {
      tanggal: '2023-10-02',
      noTransaksi: '009',
      total: 150
    },
    {
      tanggal: '2023-10-02',
      noTransaksi: '010',
      total: 150
    },
    {
      tanggal: '2023-10-02',
      noTransaksi: '011',
      total: 150
    },
    {
      tanggal: '2023-10-03',
      noTransaksi: '012',
      total: 200
    }
  ];

  const dt_last_transaction = $('.datatables-last-transaction');
  const dt_topup_products = $('.datatables-topup-products');
  let dt_lt;
  let dt_tpp;

  if (dt_last_transaction.length) {
    dt_lt = dt_last_transaction.DataTable({
      data: dataSementara,
      dom: 't',
      paging: true, // Aktifkan pagination (halaman)
      lengthChange: false,
      searching: false,
      lengthMenu: [5],
      pageLength: 10, // Tampilkan hanya 5 data per halaman
      columns: [
        { data: 'tanggal' },
        { data: 'noTransaksi' },
        { data: 'total' }
        // Kolom lainnya sesuai dengan kebutuhan Anda
      ],
      responsive: {
        details: {
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });
  }
  if (dt_topup_products.length) {
    dt_tpp = dt_topup_products.DataTable({
      data: dataSell,
      dom: 't',
      paging: true, // Aktifkan pagination (halaman)
      lengthChange: false,
      searching: false,
      lengthMenu: [5],
      pageLength: 10, // Tampilkan hanya 5 data per halaman
      columns: [
        { data: 'tanggal' },
        { data: 'total' }
        // Kolom lainnya sesuai dengan kebutuhan Anda
      ],
      responsive: {
        details: {
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });
  }
});
