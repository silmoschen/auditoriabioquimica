<html>
    <body onload='getToken()'>
    </body>
</html>

<input type="button" value="Test" onclick="getToken()" />
<div id="ttoken" />

<script type="text/javascript"/>

function getToken() {
const myHeaders = new Headers();
myHeaders.append("Content-Type", "application/json");

const raw = JSON.stringify({
  "url": "https://apis.jerarquicos.com:10712/auth/External/token",
  "grant_type": "client_credentials",
  "client_id": "108A279A-7900-4B36-9EA6-698BEC77E869",
  "client_secret": "gdEIlOE0bzzdit6WmpR5xY0n9T51vpcm"
});

const requestOptions = {
  method: "PUT",
  headers: myHeaders,
  body: raw,
  redirect: "follow"
};

fetch("http://www.shmsoft.com.ar/api/jerarquicos/token", requestOptions)
  .then((response) => response.text())
  .then((result) => document.getElementById('ttoken').innerText = result)
  .catch((error) => alert(error));
  }

</script>



