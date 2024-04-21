var Utils = {
    block_ui : function(element){
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

    },

    unblock_ui : function(element){
        $(element).unblock({});
    },

    init_spapp : function(){
        var userRole = 'admin';

                    var defaultView = userRole === 'admin' ? "#admin-dashboard" : "#employee-dashboard";

                    var app = $.spapp({
                        defaultView: defaultView,
                        templateDir: "./views/"
                    });

                    app.run();
    },

    get_query_param: function (name) {
      var regexS = "[\\?&]" + name + "=([^&#]*)",
        regex = new RegExp(regexS),
        results = regex.exec(window.location.search);
      if (results == null) {
        return "";
      } else {
        return decodeURIComponent(results[1].replace(/\+/g, " "));
      }
    },

    get_datatable: function (
      table_id,
      url,
      columns,
      disable_sort,
      callback,
      details_callback = null,
      sort_column = null,
      sort_order = null,
      draw_callback = null,
      page_length = 10
    ) {
      if ($.fn.dataTable.isDataTable("#" + table_id)) {
        details_callback = false;
        $("#" + table_id)
          .DataTable()
          .destroy();
      }
      var table = $("#" + table_id).DataTable({
        order: [
          sort_column == null ? 2 : sort_column,
          sort_order == null ? "desc" : sort_order,
        ],
        orderClasses: false,
        columns: columns,
        columnDefs: [{ orderable: false, targets: disable_sort }],
        processing: true,
        serverSide: true,
        ajax: {
          url: url,
          type: "GET",
        },
        lengthMenu: [
          [5, 10, 15, 50, 100, 200, 500, 5000],
          [5, 10, 15, 50, 100, 200, 500, "ALL"],
        ],
        pageLength: page_length,
        initComplete: function () {
          if (callback) callback();
        },
        drawCallback: function (settings) {
          if (draw_callback) draw_callback();
        },
      });
    },

    show_confirm : function(message, callback){
        bootbox.confirm({
            message: message,
            buttons: {
              confirm: {
                label: "Yes",
                className: "btn-success",
              },
              cancel: {
                label: "No",
                className: "btn-danger",
              },
            },
            callback: function (result) {
              callback(result);
            },
          });
    },

    serialize_form : function(form){
        var data = form.serializeArray();
        var obj = {};
        for (var i = 0; i < data.length; i++) {
          obj[data[i].name] = data[i].value;
        }
        return obj;
    },

    clear_form : function(form){
        form.trigger("reset");
    },



}