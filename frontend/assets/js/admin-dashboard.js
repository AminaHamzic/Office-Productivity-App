document.addEventListener("DOMContentLoaded", function () {
	fetch("./assets/mock-data/employees1.json")
		.then((response) => {
			if (!response.ok) {
				throw new Error("Network response was not ok");
			}
			return response.json();
		})
		.then((data) => {
			initializeDatatable1("employeesTable", data);
		})
		.catch((error) => {
			console.error(
				"There has been a problem with your fetch operation:",
				error
			);
		});
});



initializeDatatable1 = (tableId, data) => {
    
    $('#' + tableId).DataTable({
    
        responsive: true,
        "width": '100%',
        columns: [
            {title: "ID", data: "id"},
            {title: "Name", data: "name_surname"},
            {title: "Position", data: "position"},
            {title: "Office", data: "office"},
            {title: "Working Hours", data: "working_hours"},
        ],
        data: data
    });
};



document.addEventListener("DOMContentLoaded", function () {
    fetch("./assets/mock-data/admin-cards.json")
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            renderCards(data);
        })
        .catch((error) => {
            console.error(
                "There has been a problem with your fetch operation:",
                error
            );
        });
});

function renderCards(data) {
    const cardContainer = document.getElementById("cardContainer");

    data.forEach((cardData) => {
        const cardHtml = `
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

        cardContainer.insertAdjacentHTML("beforeend", cardHtml);
        //console.log("Card data is: " + JSON.stringify(cardData));
    });
}
