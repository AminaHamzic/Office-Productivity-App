var EmployeeService = {

    reload_employee_datatable: function () {
      Utils.get_datatable(
        "employees-table",
        Constants.API_BASE_URL + "get_employee.php",
        [
          { data: "user_id" },
          { data: "name_surname" },
          { data: "position" },
          { data: "office" },
          { data: "working_hours" },
          { data: "action"}
          
        ]
      );
    },

    add_employee: function () {
      var form = $("#add-employee-form");
      if (form.valid()) {
        var data = Utils.serialize_form(form);
        RestClient.post(
          "add_employee.php",
          data,
          function (data) {
            EmployeeService.reload_employee_datatable();
            Utils.clear_form(form);
          },
          
        );
      }
    },

    open_edit_employee_modal: function (user_id) {
      alert("EDIT");

    },
    //I want to use this modal from admin-employee-management.html to pop up window: deleteEmployeeModal
    

    delete_employee: function (id) {
      if (
        confirm(
          "Do you want to delete employee with the id " + id + "?"
        ) == true
      ) {
        RestClient.delete(
          "delete_employee.php?id=" + id,
          {},
          function (data) {
            EmployeeService.reload_employee_datatable();          
          }
        );
      }
    },
  
    
}

