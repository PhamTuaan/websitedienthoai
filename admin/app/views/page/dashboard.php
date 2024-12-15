<div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Tổng doanh thu năm 2024</p>
                            <h5 class="font-weight-bolder mb-0">
                                <span class="text-success text-sm font-weight-bolder"><?php echo number_format($totalRevenue); ?> VND</span>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Doanh thu ngày</p>
                            <h5 class="font-weight-bolder mb-0">
                                <span class="text-success text-sm font-weight-bolder"><?php echo number_format($revenueToday); ?> VND</span>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Khách hàng</p>
                            <h5 class="font-weight-bolder mb-0">
                                <?php echo $totalCustomers ?>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Sản phẩm</p>
                            <h5 class="font-weight-bolder mb-0">
                                <?php echo $totalProducts ?>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-4">
    <div class="col-lg-7">
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="start_date">Ngày bắt đầu:</label>
                    <input type="date" class="form-control" id="start_date" name="start_date">
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="end_date">Ngày kết thúc:</label>
                    <input type="date" class="form-control" id="end_date" name="end_date">
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-primary" id="buttonFilterChart">LỌC</button>
    </div>
    <div class="col-lg-12">
        <canvas id="myChart" width="400" height="200"></canvas>
    </div>
</div>
<div class="row my-4">
    <div class="col-lg-8 col-md-6 mb-md-0 mb-4" style="min-height: 480px;">
        <div class="card">
            <div class="card-header pb-0">
                <div class="row">
                    <div class="col-lg-6 col-7">
                        <h6>Giao dịch hôm nay</h6>
                        <p class="text-sm mb-0">
                            <i class="fa fa-check text-info" aria-hidden="true"></i>
                            <span class="font-weight-bold ms-1">
                                <span id="countOrderToday"></span> đơn hàng
                            </span> hôm nay
                        </p>
                    </div>
                    <div class="col-lg-6 col-5 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-ellipsis-v text-secondary"></i>
                            </a>
                            <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                                <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                                <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a></li>
                                <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Người mua</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Địa chỉ</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ghi chú</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tổng tiền</th>
                            </tr>
                        </thead>
                        <tbody id="orderToday">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6" style="min-height: 480px;">
        <div class="card h-100">
            <div class="card-header pb-0">
                <h6>Top sản phẩm bán chạy</h6>
                <p class="text-sm">
                    <i class="fa fa-arrow-up text-success" aria-hidden="true"></i>
                    <span class="font-weight-bold">24%</span> tháng naỳ
                </p>
            </div>
            <div class="card-body p-3">
                <div class="timeline timeline-one-side" id="bestSellerProduct">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const URL_GLOBAL = "http://localhost/phone-ecommerce-chat/admin/home";
    let myChart = null;

    $(document).ready(function() {

        $.ajax({
            type: 'GET',
            url: `${URL_GLOBAL}/getChart`,
            success: function(res) {
                console.log(res);
                if (res.status == 200) {
                    initChart(res.data)
                }
            },
            error: function(xhr, status, error) {
                showToast('Có lỗi xảy ra: ' + error, false);
            }
        });

        $('#start_date, #end_date').change(function() {
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            $.ajax({
                type: 'POST',
                url: `${URL_GLOBAL}/getChartFiltered`,
                data: {
                    start_date: startDate,
                    end_date: endDate
                },
                success: function(res) {
                    console.log(res);
                    if (res.status == 200) {
                        initChart(res.data);
                        showToast('Tìm kiếm theo ngày tháng thành công!', true)
                    }
                },
                error: function(xhr, status, error) {
                    showToast('Có lỗi xảy ra: ' + error, false);
                }
            });
        });

        $('#buttonFilterChart').click(function() {
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            $.ajax({
                type: 'POST',
                url: `${URL_GLOBAL}/getChartFiltered`,
                data: {
                    start_date: startDate,
                    end_date: endDate
                },
                success: function(res) {
                    console.log(res);
                    if (res.status == 200) {
                        initChart(res.data);
                        showToast('Tìm kiếm theo ngày tháng thành công!', true)
                    }
                },
                error: function(xhr, status, error) {
                    showToast('Có lỗi xảy ra: ' + error, false);
                }
            });
        })
    });

    const initChart = (data) => {
        if (myChart) {
            myChart.destroy();
        }

        var dates = data.map(function(item) {
            return item.date;
        });

        var totalOrders = data.map(function(item) {
            return item.total_orders;
        });

        var totalRevenue = data.map(function(item) {
            return item.total_revenue;
        });

        var ctx = document.getElementById('myChart').getContext('2d');
        myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Tổng số đơn đặt hàng',
                    data: totalOrders,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: 'Tổng doanh thu',
                    data: totalRevenue,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'BIỂU ĐỒ THỐNG KÊ DOANH THU',
                        font: {
                            size: 18,
                            color: 'black'
                        }
                    }
                }
            }
        });
    }

    // Init ORDER - BEST SELL Products

    // Fetching
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: `${URL_GLOBAL}/getOrderToday`,
            success: function(res) {
                console.log(res);
                if (res.status == 200) {
                    // DIsplay count Order
                    document.getElementById('countOrderToday').innerHTML = res.data.length;

                    renderOrder(res.data)
                }
            },
            error: function(xhr, status, error) {
                showToast('Có lỗi xảy ra: ' + error, false);
            }
        });

        $.ajax({
            type: 'GET',
            url: `${URL_GLOBAL}/getBestSellerProducts`,
            success: function(res) {
                console.log(res);
                if (res.status == 200) {
                    renderProduct(res.data)
                }
            },
            error: function(xhr, status, error) {
                showToast('Có lỗi xảy ra: ' + error, false);
            }
        });
    });

    // Render
    const renderOrder = (data) => {
        const countOrderToday = document.getElementById('countOrderToday');
        const orderTodayElement = document.getElementById('orderToday');
        if (data.length === 0) {
            countOrderToday.innerHTML = `<span class="ps-4 fw-bold">Không có đơn hàng hôm nay</span>`;
            return;
        }

        const orderHtml = data.map((order) => (
            `
        <tr>
            <td>
                <div class="d-flex px-2 py-1">
                    <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">${order.name_receiver}</h6>
                    </div>
                </div>
            </td>
            <td>
                <span class="text-xs font-weight-bold text-truncate" style="max-width:170px"> ${order.address_receiver ? order.address_receiver : 'Không có'}</span>
            </td>
            <td class="align-middle text-center text-sm">
                <span class="text-xs font-weight-bold text-truncate" style="max-width:170px"> ${order.notes ? order.notes : 'Không có'}</span>
            </td>
            <td class="align-middle">
                <span class="text-xs font-weight-bold">${Number(order.total_price).toLocaleString('vi-VN')} VND</span>
            </td>
        </tr>
        `
        )).join('');

        orderTodayElement.innerHTML = orderHtml;
    };

    const renderProduct = (data) => {
        const bestSellerProductElement = document.getElementById('bestSellerProduct');
        const productHtml = data.map(product => `
        <div class="timeline-block mb-3">
            <span class="timeline-step">
                <i class="ni ni-cart text-info text-gradient"></i>
            </span>
            <div class="timeline-content">
                <h6 class="text-dark text-sm font-weight-bold mb-0">${product.product_name}</h6>
                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">${Number(product.price).toLocaleString('vi-VN')} VND</p>
            </div>
        </div>
    `).join('');

        bestSellerProductElement.innerHTML = productHtml;
    };
</script>