let employees = [];
var idCounter = 1;
/*
$("#add-employee-form").validate({
    rules: {
        name_surname: {
            required: true,
            minlength: 3,
            maxlength: 255,
            
        },
        position: {
            required: true,
            
        },
        office: {
            required: true,
            digits: true,

            
        },
        working_hours : {
            required: true,
            digits: true,
            
        }
 },
messages: {
    name_surname: {
        required: "Please enter your name and surname",
        minlength: "Name and surname must be at least 3 characters long",
        maxlength: "Name and surname must be less than 255 characters long",
    },
    position: {
        required: "Please enter your position",
        
    },
    office: {
        required: "Please enter your office",
        digits: "Must contain only numbers"
        
    },
    working_hours: {
        required: "Please enter your working hours",
        digits: "Must contain only numbers"



        
    }
},

submitHandler: function(form, event) {
    event.preventDefault();
    blockUi("#add-employee-form");
    let formData = serializeForm(form);

    //console.log("Json data is: " + JSON.stringify(formData));
    formData["id"] = idCounter++;
    employees.push(formData);

    if($.fn.dataTable.isDataTable("#employees-table")) {
        $("#employees-table").DataTable().destroy();
    }
    initializeDatatable("employees-table", employees);



    $("#add-employee-form")[0].reset(); //clean form
    //console.log("Employees array is: " + JSON.stringify(employees));


    unblockUi("#add-employee-form");
}
});*/

const blockUi = (element) => {
    
  };
  
const unblockUi = (element) => {
};


serializeForm = (form) => {
   

}

/*
initializeDatatable = (tableId, data) => {
    
    $('#' + tableId).DataTable({
        responsive: true,
        columns: [
            //{title: "ID", data: "id"},
            {title: "Name", data: "name_surname"},
            {title: "Position", data: "position"},
            {title: "Office", data: "office"},
            {title: "Working Hours", data: "working_hours"},
            {title: "Actions", data: "id", render: function(data) {
                return `<button class="btn btn-danger" onclick="deleteEmployee(${data})">Delete</button>`;
            }},
            {title: "Actions", data: "id", render: function(data) {
                return `<button class="btn btn-info" onclick="updateEmployee(${data})">Update</button>`;
            }}
        ],
        data: data
    });
};


updateEmployee = (id) => {
    var selectedUser = {};
    $.each(employees, (idx, user) =>{
        if(user.id === id) {
            selectedUser = user;
            return false; 
        }
    });

    if($.isEmptyObject(selectedUser)) {
        console.log("User with ID " + id + " not found.");
        return;
    }

    $("#update-employee-form input[name='id']").val(selectedUser.id);
    $("#update-employee-form input[name='name_surname']").val(selectedUser.name_surname);
    $("#update-employee-form input[name='position']").val(selectedUser.position);
    $("#update-employee-form input[name='office']").val(selectedUser.office);
    $("#update-employee-form input[name='working_hours']").val(selectedUser.working_hours);

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
*/







