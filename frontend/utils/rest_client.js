var RestClient = {
  get: function (url, callback, error_callback) {
      $.ajax({
          url: Constants.API_BASE_URL + url,
          type: "GET",
          success: function (response) {
              if (callback) callback(response);
          },
          error: function (jqXHR, textStatus, errorThrown) {
              if (error_callback) error_callback(jqXHR);
              else toastr.error("Error: " + textStatus + " - " + errorThrown);
          }
      });
  },
  request: function (url, method, data, callback, error_callback) {
      $.ajax({
          url: Constants.API_BASE_URL + url,
          type: method,
          data: data,
          contentType: 'application/json', // Ensure the content type is set to JSON
          processData: false, // Prevent jQuery from auto-processing data into query string format
      })
      .done(function (response, status, jqXHR) {
          if (callback) callback(response);
      })
      .fail(function (jqXHR, textStatus, errorThrown) { // Changed from .error to .fail
          if (error_callback) error_callback(jqXHR);
          else toastr.error("Request failed: " + jqXHR.responseText);
      });
  },
  post: function (url, data, callback, error_callback) {
      RestClient.request(url, "POST", JSON.stringify(data), callback, error_callback);
  },
  delete: function (url, data, callback, error_callback) {
      RestClient.request(url, "DELETE", JSON.stringify(data), callback, error_callback);
  },
  put: function (url, data, callback, error_callback) {
      RestClient.request(url, "PUT", JSON.stringify(data), callback, error_callback);
  },
};
