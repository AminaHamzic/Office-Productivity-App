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
      // Make a REST call to fetch the employee data
      RestClient.get("update_employee.php?id=" + user_id, function (data) {
        // Populate the form fields in the modal with the fetched data
        $('#update-id').val(data.user_id);
        $('#update-name_surname').val(data.name_surname);
        $('#update-position').val(data.position);
        $('#update-office').val(data.office);
        $('#update-working_hours').val(data.working_hours);
        
        // Show the modal for editing
        $('#updateEmployeeModal').modal('show');
      }, function (error) {
        toastr.error("Error loading employee data.");
      });
    },
    
    save_employee_changes: function () {
      var form = $("#update-employee-form");
      if (form.valid()) {
        var data = Utils.serialize_form(form);
        // No need to append the id to the URL, it should be part of the form data
        RestClient.post(
          "update_employee.php", // the endpoint
          data, // the payload includes the id
          function (response) {
            $('#updateEmployeeModal').modal('hide');
            EmployeeService.reload_employee_datatable();
            toastr.success("Employee has been updated successfully.");
          },
          function (error) {
            $('#updateEmployeeModal').modal('hide');
            toastr.error("Error updating the employee.");
          }
        );
      }
    },
    

  
    delete_employee: function (id) {
      $('#deleteEmployeeModal').data('employeeId', id);
      $('#deleteEmployeeModal').modal('show');
    },

    confirm_delete_employee: function () {
      var id = $('#deleteEmployeeModal').data('employeeId');

      RestClient.delete(
        "delete_employee.php?id=" + id,
        {},
        function (data) {
          $('#deleteEmployeeModal').modal('hide');
      
          EmployeeService.reload_employee_datatable();
          
          toastr.success("Employee has been deleted successfully.");
        },
        function (error) {
          $('#deleteEmployeeModal').modal('hide');
          
          toastr.error("Error deleting the employee.");
        }
      );
    },

  }


  