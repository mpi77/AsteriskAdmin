/**
 * Root validator.
 * 
 * @author MPI
 * @version 1.2
 */
switch (VALIDATOR) {
case "renew":
	$(document).ready(function() {
		var form = $("#user_renew");
		var email = $("input[type=text][name=renew_value]");
		email.blur(validateEmail);
		email.keyup(validateEmail);
		form.submit(function() {
			if (validateEmail())
				return true;
			else
				return false;
		});
		function validateEmail() {
			var filter = /^[^.]+(\.[^.]+)*@([^.]+[.])+[a-z]{2,4}$/;
			if (filter.test(email.val())) {
				email.removeClass("error");
				return true;
			} else {
				email.addClass("error");
				return false;
			}
		}
	});
	break;

case "login":
	$(document).ready(function() {
		var form = $("#user_login");
		var email = $("input[type=text][name=email]");
		var pass = $("input[type=password][name=password]");
		email.blur(validateEmail);
		pass.blur(validatePassword);
		email.keyup(validateEmail);
		pass.keyup(validatePassword);
		form.submit(function() {
			if (validateEmail() && validatePassword())
				return true;
			else
				return false;
		});
		function validateEmail() {
			var filter = /^[^.]+(\.[^.]+)*@([^.]+[.])+[a-z]{2,4}$/;
			if (filter.test(email.val())) {
				email.removeClass("error");
				return true;
			} else {
				email.addClass("error");
				return false;
			}
		}
		function validatePassword() {
			var filter = /^[a-zA-Z0-9,\.-]{6,50}$/;
			if (filter.test(pass.val())) {
				pass.removeClass("error");
				return true;
			} else {
				pass.addClass("error");
				return false;
			}
		}
	});
	break;

case "user_request":
	$(document).ready(function() {
		var form = $("#user_edit");
		var email = $("input[type=text][name=email]");
		email.blur(validateEmail);
		email.keyup(validateEmail);
		form.submit(function() {
			if (validateEmail())
				return true;
			else
				return false;
		});
		function validateEmail() {
			var filter = /^[^.]+(\.[^.]+)*@([^.]+[.])+[a-z]{2,4}$/;
			if (filter.test(email.val())) {
				email.removeClass("error");
				return true;
			} else {
				email.addClass("error");
				return false;
			}
		}
	});
	break;

case "user_setpassword":
	$(document).ready(function() {
		var form = $("#user_setpassword");
		var pass_1 = $("input[type=password][name=password_1]");
		var pass_2 = $("input[type=password][name=password_2]");
		pass_1.blur(validatePassword1);
		pass_2.blur(validatePassword2);
		pass_1.keyup(validatePassword1);
		pass_2.keyup(validatePassword2);
		form.submit(function() {
			if (validatePassword1() && validatePassword2())
				return true;
			else
				return false;
		});
		function validatePassword1() {
			var filter = /^[a-zA-Z0-9,\.-]{6,50}$/;
			if (filter.test(pass_1.val())) {
				pass_1.removeClass("error");
				return true;
			} else {
				pass_1.addClass("error");
				return false;
			}
		}
		function validatePassword2() {
			var filter = /^[a-zA-Z0-9,\.-]{6,50}$/;
			if (filter.test(pass_2.val())) {
				pass_2.removeClass("error");
				return true;
			} else {
				pass_2.addClass("error");
				return false;
			}
		}
	});
	break;

case "user_create":
	$(document).ready(function() {
		var form = $("#user_create");
		var pass_1 = $("input[type=password][name=password_1]");
		var pass_2 = $("input[type=password][name=password_2]");
		pass_1.blur(validatePassword1);
		pass_2.blur(validatePassword2);
		pass_1.keyup(validatePassword1);
		pass_2.keyup(validatePassword2);
		form.submit(function() {
			if (validatePassword1() && validatePassword2())
				return true;
			else
				return false;
		});
		function validatePassword1() {
			var filter = /^[a-zA-Z0-9,\.-]{6,50}$/;
			if (filter.test(pass_1.val())) {
				pass_1.removeClass("error");
				return true;
			} else {
				pass_1.addClass("error");
				return false;
			}
		}
		function validatePassword2() {
			var filter = /^[a-zA-Z0-9,\.-]{6,50}$/;
			if (filter.test(pass_2.val())) {
				pass_2.removeClass("error");
				return true;
			} else {
				pass_2.addClass("error");
				return false;
			}
		}
	});
	break;

case "user_edit":
	$(document).ready(
			function() {
				var form1 = $("#user_edit");
				var form2 = $("#user_edit_pass");
				var fname = $("input[type=text][name=first_name]");
				var lname = $("input[type=text][name=last_name]");
				var phone = $("input[type=text][name=phone]");
				var pass_old = $("input[type=password][name=password_old]");
				var pass_1 = $("input[type=password][name=password_1]");
				var pass_2 = $("input[type=password][name=password_2]");
				fname.blur(validateFname);
				lname.blur(validateLname);
				phone.blur(validatePhone);
				pass_old.blur(validatePasswordOld);
				pass_1.blur(validatePassword1);
				pass_2.blur(validatePassword2);
				fname.keyup(validateFname);
				lname.keyup(validateLname);
				phone.keyup(validatePhone);
				pass_old.keyup(validatePasswordOld);
				pass_1.keyup(validatePassword1);
				pass_2.keyup(validatePassword2);
				form1.submit(function() {
					if (validateFname() && validateLname() && validatePhone())
						return true;
					else
						return false;
				});
				form2.submit(function() {
					if (validatePasswordOld() && validatePassword1()
							&& validatePassword2())
						return true;
					else
						return false;
				});
				function validateFname() {
					var filter = /[\w\s]{0,50}/;
					if (filter.test(fname.val())) {
						fname.removeClass("error");
						return true;
					} else {
						fname.addClass("error");
						return false;
					}
				}
				function validateLname() {
					var filter = /[\w\s]{0,50}/;
					if (filter.test(lname.val())) {
						lname.removeClass("error");
						return true;
					} else {
						lname.addClass("error");
						return false;
					}
				}
				function validatePhone() {
					var filter = /^((\+[0-9]{3})?([0-9]{9}))?$/;
					if (filter.test(phone.val())) {
						phone.removeClass("error");
						return true;
					} else {
						phone.addClass("error");
						return false;
					}
				}
				function validatePasswordOld() {
					var filter = /^[a-zA-Z0-9,\.-]{6,50}$/;
					if (filter.test(pass_old.val())) {
						pass_old.removeClass("error");
						return true;
					} else {
						pass_old.addClass("error");
						return false;
					}
				}
				function validatePassword1() {
					var filter = /^[a-zA-Z0-9,\.-]{6,50}$/;
					if (filter.test(pass_1.val())) {
						pass_1.removeClass("error");
						return true;
					} else {
						pass_1.addClass("error");
						return false;
					}
				}
				function validatePassword2() {
					var filter = /^[a-zA-Z0-9,\.-]{6,50}$/;
					if (filter.test(pass_2.val())) {
						pass_2.removeClass("error");
						return true;
					} else {
						pass_2.addClass("error");
						return false;
					}
				}
			});
	break;

case "extension":
	$(document).ready(function() {
				var form = $("#ext_edit");
				var context = $("input[type=text][name=ext_context]");
				var line = $("input[type=text][name=ext_line]");
				var priority = $("input[type=text][name=ext_priority]");
				var app = $("input[type=text][name=ext_app]");
				var appdata = $("input[type=text][name=ext_appdata]");

				context.blur(validateContext);
				line.blur(validateLine);
				priority.blur(validatePriority);
				app.blur(validateApp);
				appdata.blur(validateAppdata);

				context.keyup(validateContext);
				line.keyup(validateLine);
				priority.keyup(validatePriority);
				app.keyup(validateApp);
				appdata.keyup(validateAppdata);

				form.submit(function() {
					if (validateContext() && validateLine()
							&& validatePriority() && validateApp()
							&& validateAppdata())
						return true;
					else
						return false;
				});

				function validateContext() {
					var filter = /^[a-zA-Z0-9,\.-]{1,20}$/;
					if (filter.test(context.val())) {
						context.removeClass("error");
						return true;
					} else {
						context.addClass("error");
						return false;
					}
				}
				function validateLine() {
					var filter = /^[0-9_ZXis\(\)\[\]\{\}\*]{1,30}$/;
					if (filter.test(line.val())) {
						line.removeClass("error");
						return true;
					} else {
						line.addClass("error");
						return false;
					}
				}
				function validatePriority() {
					var filter = /^[0-9]{1,3}$/;
					if (filter.test(priority.val())) {
						priority.removeClass("error");
						return true;
					} else {
						priority.addClass("error");
						return false;
					}
				}
				function validateApp() {
					var filter = /^[a-zA-Z0-9,\.-_]{1,20}$/;
					if (filter.test(app.val())) {
						app.removeClass("error");
						return true;
					} else {
						app.addClass("error");
						return false;
					}
				}
				function validateAppdata() {
					var filter = /^[a-zA-Z0-9,\.-_@\$\?:\/\(\)\[\]\{\}=\"\' ]{1,100}$/;
					if (filter.test(appdata.val())) {
						appdata.removeClass("error");
						return true;
					} else {
						appdata.addClass("error");
						return false;
					}
				}
			});
	break;

case "pstn":
	$(document).ready(function() {
		var form = $("#ext_edit");
		var pstn_num = $("input[type=text][name=pstn_number]");
		pstn_num.blur(validatePstnNumber);
		pstn_num.keyup(validatePstnNumber);
		form.submit(function() {
			if (validatePstnNumber())
				return true;
			else
				return false;
		});
		function validatePstnNumber() {
			var filter = /^(00[0-9]{3})?([0-9]{9})$/;
			if (filter.test(pstn_num.val())) {
				pstn_num.removeClass("error");
				return true;
			} else {
				pstn_num.addClass("error");
				return false;
			}
		}
	});
	break;
	
case "line":
	$(document).ready(
			function() {
				var form1 = $("#line_edit_tab_1");
				var form2 = $("#line_edit_tab_2");
				var form5 = $("#line_edit_tab_5");
				
				var line_number = $("input[type=text][name=line_number]");
				var line_name = $("input[type=text][name=line_name]");
				var line_secret_1 = $("input[type=password][name=line_secret_1]");
				var line_secret_2 = $("input[type=password][name=line_secret_2]");
				var vm_secret = $("input[type=text][name=line_voicemail_pass]");
				var ip = $("input[type=text][name=line_ip]");
				var sm = $("input[type=text][name=line_sm]");
				
				line_number.blur(validateLineNumber);
				line_name.blur(validateLineName);
				line_secret_1.blur(validateLineSecret1);
				line_secret_2.blur(validateLineSecret2);
				vm_secret.blur(validateVoicemailPass);
				ip.blur(validateIp);
				sm.blur(validateSm);
				
				line_number.keyup(validateLineNumber);
				line_name.keyup(validateLineName);
				line_secret_1.keyup(validateLineSecret1);
				line_secret_2.keyup(validateLineSecret2);
				vm_secret.keyup(validateVoicemailPass);
				ip.keyup(validateIp);
				sm.keyup(validateSm);
				
				form1.submit(function() {
					if (validateLineName())
						return true;
					else
						return false;
				});
				form2.submit(function() {
					if (validateIp() && validateSm()
							&& validateVoicemailPass())
						return true;
					else
						return false;
				});
				form5.submit(function() {
					if (validateLineSecret1() && validateLineSecret2())
						return true;
					else
						return false;
				});
				function validateLineNumber() {
					var filter = /^[1-9]{1}[0-9]{3}$/;
					if (line_number.length > 0 && filter.test(line_number.val())) {
						line_number.removeClass("error");
						return true;
					} else {
						line_number.addClass("error");
						return false;
					}
				}
				function validateLineName() {
					var filter = /^[a-zA-Z0-9,\.-]{2,50}$/;
					if (filter.test(line_name.val())) {
						line_name.removeClass("error");
						return true;
					} else {
						line_name.addClass("error");
						return false;
					}
				}
				function validateLineSecret1() {
					var filter = /^[a-zA-Z0-9,\.-]{6,50}$/;
					if (filter.test(line_secret_1.val())) {
						line_secret_1.removeClass("error");
						return true;
					} else {
						line_secret_1.addClass("error");
						return false;
					}
				}
				function validateLineSecret2() {
					var filter = /^[a-zA-Z0-9,\.-]{6,50}$/;
					if (filter.test(line_secret_2.val())) {
						line_secret_2.removeClass("error");
						return true;
					} else {
						line_secret_2.addClass("error");
						return false;
					}
				}
				function validateIp() {
					var filter = /^([01]?\d\d?|2[0-4]\d|25[0-5])\.([01]?\d\d?|2[0-4]\d|25[0-5])\.([01]?\d\d?|2[0-4]\d|25[0-5])\.([01]?\d\d?|2[0-4]\d|25[0-5])$/;
					if(ip.val().length > 0){
						if (filter.test(ip.val())) {
							ip.removeClass("error");
							return true;
						} else {
							ip.addClass("error");
							return false;
						}
					} else{
						sm.removeClass("error");
						return true;
					}
				}
				function validateSm() {
					var filter = /^([01]?\d\d?|2[0-4]\d|25[0-5])\.([01]?\d\d?|2[0-4]\d|25[0-5])\.([01]?\d\d?|2[0-4]\d|25[0-5])\.([01]?\d\d?|2[0-4]\d|25[0-5])$/;
					if(sm.val().length > 0){
						if (filter.test(sm.val())) {
							sm.removeClass("error");
							return true;
						} else {
							sm.addClass("error");
							return false;
						}
					} else{
						sm.removeClass("error");
						return true;
					}
				}
				function validateVoicemailPass() {
					var filter = /^[0-9]{4,8}$/;
					if(vm_secret.val().length > 0){
						if (filter.test(vm_secret.val())) {
							vm_secret.removeClass("error");
							return true;
						} else {
							vm_secret.addClass("error");
							return false;
						}
					} else{
						vm_secret.removeClass("error");
						return true;
					}
				}
			});
	break;
default:
	break;
}