function validateForm() {
    let phone = document.getElementById("phone").value;
    let engine = document.getElementById("engine").value;
    let mechanic = document.getElementById("mechanic").value;
    let date = document.getElementById("date").value;

    if (!/^\d+$/.test(phone)) {
        alert("Phone number must contain only digits.");
        return false;
    }
    if (!/^\d+$/.test(engine)) {
        alert("Car Engine number must contain only digits.");
        return false;
    }

    if (mechanic === "") {
        alert("Please select a mechanic.");
        return false;
    }

    if (date === "") {
        alert("Please select an appointment date.");
        return false;
    }

    return true;
}