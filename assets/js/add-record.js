document.getElementById("type").addEventListener("change", function () {

	if (this.value === "MX") {
		document.getElementById("prio-group").className += " show";
	} else if (this.value === "SRV") {
		document.getElementById("prio-group").className += " show";
		document.getElementById("weight-group").className += " show";
		document.getElementById("port-group").className += " show";
	}

});
