var FormValidation = {

    serializeForm: function(form){
        let jsonResult = {};
        $.each(form.serializeArray(), function () {
            jsonResult[this.name] = this.value;
        });
        return jsonResult;
    },

    validate: function (form_selectior, form_rules, form_submit_handler_callback){
        var form_object = $(form_selectior);
        var success = $(".alert-success", form_object);
        var error = $(".alert-danger", form_object);

        $(form_object).validate({
            rules: form_rules,
            submitHandler: function(form, event) {
                event.preventDefault(); //prevent default form submit 
                success.show();
                error.hide();
                if(form_submit_handler_callback){

                    form_submit_handler_callback(
                        FormValidation.serializeForm(form_object)
                    );
                    //console.log("Form submitted");
                    //console.log(FormValidation.serializeForm(form_object));
                }
            }
            });
    }
}