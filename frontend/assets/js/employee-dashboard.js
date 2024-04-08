document.addEventListener("DOMContentLoaded", function () {
    fetch("./assets/mock-data/employee-cards.json")
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            renderCardsEmployee(data);
        })
        .catch((error) => {
            console.error(
                "There has been a problem with your fetch operation:",
                error
            );
        });
});

function renderCardsEmployee(data) {
    const cardContainer1 = document.getElementById("cardContainer1");

    data.forEach((cardData) => {
        const cardHtml1 = `
                <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100 bg-primary">
                <div class="card-body">
                    <h5 class="card-title bg-primary text-white fw-bold">${cardData.title}</h5>
                    <p class="bg-primary text-white fw-bold">${cardData.body}</p>
                </div>
                <div class="card-footer bg-primary border-top d-flex align-items-center justify-content-between">
                    <a class="btn btn-outline-light stretched-link" href="${cardData.link}">${cardData.linkLabel}</a>
                    <div><i class="fas fa-angle-right bg-primary text-white"></i></div>
                </div>
            </div>
        </div>  `;

        cardContainer1.insertAdjacentHTML("beforeend", cardHtml1);
        //console.log("Card data is: " + JSON.stringify(cardData));
    });
}



document.addEventListener("DOMContentLoaded", function () {
    fetch("./assets/mock-data/expenses.json")
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            renderChart(data);
        })
        .catch((error) => {
            console.error(
                "There has been a problem with your fetch operation:",
                error
            );
        });
});

function renderChart(data) {
    Highcharts.chart('highchartsContainer', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Your expenses this week',
            align: 'left'
        },
        xAxis: {
            categories: data.categories,
            crosshair: true,
            accessibility: {
                description: 'Categories'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Expense Amount'
            }
        },
        tooltip: {
            valueSuffix: ' KM'
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: data.series
    });
}
