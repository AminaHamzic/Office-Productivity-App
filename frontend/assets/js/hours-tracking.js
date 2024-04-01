document.addEventListener("DOMContentLoaded", function () {
    fetch("./assets/mock-data/employees1.json")
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            // Assuming the JSON structure is { "weekly_working_hours": 40 }
            const weeklyWorkingHours = data.weekly_working_hours;
            console.log("Weekly working hours:", weeklyWorkingHours);

            document.getElementById("weekly_working_hours").textContent = weeklyWorkingHours;
        })
        .catch((error) => {
            console.error(
                "There has been a problem with your fetch operation:",
                error
            );
        });
});


let hours = [];
var idCounter = 1;

$("#hours-form").validate({
    rules: {
        hours : {
            required: true,
            digits: true,
            
        },
        hoursDate: {
            required: true,
          
            
        },
        hoursDay: {
            required: true,
            
            
        }
 },
messages: {
    
    hours: {
        required: "Please enter your working hours",
        digits: "Must contain only numbers"

    },
    hoursDate: {
        required: "Please enter the date",
        
    },
    hoursDay: {
        required: "Please enter the day",
        
    }
},

submitHandler: function(form, event) {
    event.preventDefault();
    blockUi("#hours-form");
    let formData = serializeForm(form);

    //console.log("Json data is: " + JSON.stringify(formData));
    formData["id"] = idCounter++;
    hours.push(formData);

    if($.fn.dataTable.isDataTable("#hours-table")) {
        $("#hours-table").DataTable().destroy();
    }
    initializeDatatable("hours-table", hours);



    $("#hours-form")[0].reset(); //clean form
    //console.log("hours array is: " + JSON.stringify(hours));


    unblockUi("#hours-form");
}
});

blockUi = (element) => {
    $(element).block({
      message: '<div class="spinner-border text-primary" role="status"></div>',
      css: {
        backgroundColor: "transparent",
        border: "0",
      },
      overlayCSS: {
        backgroundColor: "#000",
        opacity: 0.25,
      },
    });
  };
  
unblockUi = (element) => {
    $(element).unblock({});
};


serializeForm = (form) => {
    let jsonResult = {};
    $.each($(form).serializeArray(), function () {
        jsonResult[this.name] = this.value;
    });
    return jsonResult;

}


initializeDatatable = (tableId, data) => {
    
    $('#' + tableId).DataTable({
        responsive: true,
        columns: [
            {title: "ID", data: "id"},
            {title: "Date", data: "hoursDate"},
            {title: "Day", data: "hoursDay"},
            {title: "Hours", data: "hours"},
        ],
        data: data
    });
};


updateHours = (id) => {
    var selectedUser = {};
    $.each(hours, (idx, user) =>{
        if(user.id === id) {
            selectedUser = user;
            return false; // Exit the loop once the user is found
        }
    });

    // Check if a user with the given ID was found
    if($.isEmptyObject(selectedUser)) {
        console.log("User with ID " + id + " not found.");
        return; // Exit the function if no user was found
    }

    // Populate the update form fields with the selected user's data
    $("#update-employee-form input[name='id']").val(selectedUser.id);
    $("#update-employee-form input[name='name_surname']").val(selectedUser.name_surname);
    $("#update-employee-form input[name='position']").val(selectedUser.position);
    $("#update-employee-form input[name='office']").val(selectedUser.office);
    $("#update-employee-form input[name='working_hours']").val(selectedUser.working_hours);

    // Open the update modal or perform any other necessary actions
    $('#updateEmployeeModal').modal('show');
}

function submitUpdate(id) {
    $("#confirmSubmitButton").data("employeeId", id);
    
    $("#updateEmployeeModal").modal("show");
}

$("#confirmSubmitButton").click(function() {
    var id = $(this).data("employeeId");

    console.log("Changes submited for employee with ID:", id);

    
    $("#updateEmployeeModal").modal("hide");
});



function deleteEmployee(id) {
    $("#confirmDeleteButton").data("employeeId", id);
    
    $("#deleteEmployeeModal").modal("show");
}

$("#confirmDeleteButton").click(function() {
    var id = $(this).data("employeeId");

    console.log("Deleting employee with ID:", id);

    
    $("#deleteEmployeeModal").modal("hide");
});




