const tasks = document.querySelectorAll('.task');

function isTimeInInterval(currentTime, startTime, endTime) {
    const start = new Date(`1970-01-01T${startTime}Z`);
    const current = new Date(`1970-01-01T${currentTime}Z`);
    const end = new Date(`1970-01-01T${endTime}Z`);

    return current >= start && current <= end;
}

function check_task(element) {
    let now = new Date().toLocaleTimeString();

    const startTime = element.getAttribute("data-value1");
    const endTime = element.getAttribute("data-value2");

    if (isTimeInInterval(now, startTime, endTime)) {
        element.classList.add("show");
    }else {
        element.classList.remove("show");
    }
}

setInterval(() => {
    tasks.forEach(task => {
        check_task(task)
    })
}, 60000)
