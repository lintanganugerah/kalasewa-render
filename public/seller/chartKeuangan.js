document.addEventListener("DOMContentLoaded", function () {
    const monthlyIncomeData = JSON.parse(
        document.getElementById("monthlyIncomeData").textContent
    );
    const dailyIncomeData = JSON.parse(
        document.getElementById("dailyIncomeData").textContent
    );
    const yearlyIncomeData = JSON.parse(
        document.getElementById("yearlyIncomeData").textContent
    );

    const monthLabels = Object.keys(monthlyIncomeData);
    const incomeData = monthLabels.map(
        (month) => monthlyIncomeData[month].total_income
    );
    const ordersData = monthLabels.map(
        (month) => monthlyIncomeData[month].total_orders
    );

    new Chart(document.getElementById("monthlyIncomeChart"), {
        type: "bar",
        data: {
            labels: monthLabels,
            datasets: [
                {
                    label: "Penghasilan Bulanan",
                    data: incomeData,
                    backgroundColor: "rgba(54, 162, 235, 0.6)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 1,
                },
                {
                    label: "Total Order Bulanan",
                    data: ordersData,
                    backgroundColor: "rgba(75, 192, 192, 0.6)",
                    borderColor: "rgba(75, 192, 192, 1)",
                    borderWidth: 1,
                    borderDash: [5, 5], // Garis putus-putus untuk membedakan
                    hidden: true,
                },
            ],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        title: function (tooltipItems) {
                            const month = tooltipItems[0].label;
                            return month;
                        },
                        label: function (tooltipItem) {
                            return [
                                `Penghasilan: ${
                                    incomeData[tooltipItem.dataIndex]
                                }`,
                                `Total Order: ${
                                    ordersData[tooltipItem.dataIndex]
                                }`,
                            ];
                        },
                    },
                },
                legend: {
                    display: false,
                },
            },
        },
    });

    new Chart(document.getElementById("dailyIncomeChart"), {
        type: "line",
        data: {
            labels: Object.keys(dailyIncomeData),
            datasets: [
                {
                    label: "Penghasilan",
                    data: Object.keys(dailyIncomeData).map((date) => ({
                        x: new Date(date),
                        y: dailyIncomeData[date].total_income,
                    })),
                    backgroundColor: "rgba(255, 99, 132, 0.6)",
                    borderColor: "rgba(255, 99, 132, 1)",
                    borderWidth: 1,
                },
                {
                    label: "Total Order",
                    data: Object.keys(dailyIncomeData).map((date) => ({
                        x: new Date(date),
                        y: dailyIncomeData[date].total_orders,
                    })),
                    backgroundColor: "rgba(75, 192, 192, 0.6)",
                    borderColor: "rgba(75, 192, 192, 1)",
                    borderWidth: 1,
                    hidden: true, // Agar data total orders tidak ditampilkan di grafik utama, hanya di tooltip
                },
            ],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        title: function (tooltipItems) {
                            const date = tooltipItems[0].label;
                            return date;
                        },
                        label: function (tooltipItem) {
                            const dailyIncomeData =
                                tooltipItem.chart.data.datasets[0].data[
                                    tooltipItem.dataIndex
                                ];
                            const dailyOrderData =
                                tooltipItem.chart.data.datasets[1].data[
                                    tooltipItem.dataIndex
                                ];

                            const income = dailyIncomeData.y;
                            const orders = dailyOrderData.y;

                            return [
                                `Penghasilan: ${income}`,
                                `Total Order: ${orders}`,
                            ];
                        },
                    },
                },
                legend: {
                    display: false,
                },
            },
        },
    });

    new Chart(document.getElementById("yearlyIncomeChart"), {
        type: "bar",
        data: {
            labels: Object.keys(yearlyIncomeData),
            datasets: [
                {
                    label: "Penghasilan Pertahun",
                    data: Object.keys(yearlyIncomeData).map((year) => ({
                        x: year,
                        y: yearlyIncomeData[year].total_income,
                    })),
                    backgroundColor: "rgba(54, 162, 235, 0.6)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 1,
                },
                {
                    label: "Total Orders",
                    data: Object.keys(yearlyIncomeData).map((year) => ({
                        x: year,
                        y: yearlyIncomeData[year].total_orders,
                    })),
                    backgroundColor: "rgba(75, 192, 192, 0.6)",
                    borderColor: "rgba(75, 192, 192, 1)",
                    borderWidth: 1,
                    borderDash: [5, 5], // Garis putus-putus untuk membedakan
                    hidden: true,
                },
            ],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        title: function (tooltipItems) {
                            // Menampilkan tahun sebagai judul tooltip
                            return `Tahun ${tooltipItems[0].label}`;
                        },
                        label: function (tooltipItem) {
                            // Mengambil data yang sesuai dari tooltipItem
                            const dataset = tooltipItem.dataset;
                            const dataIndex = tooltipItem.dataIndex;
                            const value = dataset.data[dataIndex].y;

                            return [
                                `Penghasilan: ${dataset.data[dataIndex].y}`,
                                `Total Order: ${dataIndex + 1}`,
                            ];
                        },
                    },
                },
                legend: {
                    display: false,
                },
            },
        },
    });
});
