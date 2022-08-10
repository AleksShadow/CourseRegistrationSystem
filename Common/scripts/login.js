console.log("Script is running...");

//$("input[type='text']").on("click", function() {
$("input").on("click", function() {
            $(this).select();
            // console.log("something happend" + this.id);
            
            if (this.id == "pin1") {
                $('#pin1ErrorMsg').text("");
                $('#pinErrorMsg').text("");
                
            }
            
            if (this.id == "studentId") {
                $('#studentIdErrorMsg').text("");
                $('#pinErrorMsg').text("");
            }
            
            if (this.id == "name") {
                $('#nameErrorMsg').text("");
            }
            
            if (this.id == "phoneNumber") {
                $('#phoneNumberErrorMsg').text("");
            }
            
            if (this.id == "pin2") {
                $('#pin1ErrorMsg').text("");
            }
            
            
        });
        
        
        //$("input").focus(function() {
        //    $(this).select();
        //    console.log("something happend" + this.id);
        //});
        //$("input").focusin(function() {
        //    $(this).select();
        //});