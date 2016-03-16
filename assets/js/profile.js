$(document).ready(function(){
	USER_ID = $("[name='id']").val();

	$nick = $("[name='nick']");
	$email = $("[name='email']");
        $newPassword = $("[name='newPassword']");
        $repeatPassword = $("[name='repeatPassword']");
	$form = $("form");
	$submitButton = $("button");

	$nick.on("keyup", nickIsAvailable);
	$email.on("keyup", emailIsAvailable);
        $newPassword.on("keyup", checkPasswords);        
        $repeatPassword.on("keyup", checkPasswords);
	$form.on("submit", validateProfileForm);
});

function checkPasswords(){
    if(!isNotFilled($newPassword) && !isNotFilled($repeatPassword)){
        if( $newPassword.val() !== $repeatPassword.val()){
            $repeatPassword.popover("show");
        }else{
            $repeatPassword.popover("hide");
        }            
    }else{
        $repeatPassword.popover("hide");
    }
}

function validateProfileForm(e){
	if($(".popover").is(":visible")){
		e.preventDefault();
	}
}