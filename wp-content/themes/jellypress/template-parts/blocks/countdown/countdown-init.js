(function ($) {
  var countDownTotal,
    countDownHours,
    countDownMinutes,
    countDownSeconds,
    countDownEndtime,
    countDownInterval,
    countdownClock;

  function jfGetTimeRemaining(countDownEndtime) {
    countDownTotal = Date.parse(countDownEndtime) - Date.parse(new Date());
    countDownSeconds = Math.floor((countDownTotal / 1000) % 60);
    countDownMinutes = Math.floor((countDownTotal / 1000 / 60) % 60);
    countDownHours = Math.floor((countDownTotal / (1000 * 60 * 60)) % 24);
    countDownDays = Math.floor(countDownTotal / (1000 * 60 * 60 * 24));

    return {
      countDownTotal,
      countDownSeconds,
      countDownMinutes,
      countDownHours,
      countDownDays,
    };
  }

  function jfUpdateClock() {
    const t = jfGetTimeRemaining(countDownEndtime);
    daysSpan = countdownClock.querySelector(".days");
    hoursSpan = countdownClock.querySelector(".hours");
    minutesSpan = countdownClock.querySelector(".minutes");
    secondsSpan = countdownClock.querySelector(".seconds");

    if (t.countDownTotal >= 0) {
      daysSpan.innerHTML = ("0" + t.countDownDays).slice(-2);
      hoursSpan.innerHTML = ("0" + t.countDownHours).slice(-2);
      minutesSpan.innerHTML = ("0" + t.countDownMinutes).slice(-2);
      secondsSpan.innerHTML = ("0" + t.countDownSeconds).slice(-2);
    }
    if (t.countDownTotal <= 0) {
      countdownClock.classList.add("complete");
      clearInterval(countDownInterval);
    }
  }

  function initCountdown() {
    countdownClock = document.querySelector(".countdown");
    if (countdownClock) {
      countDownEndtime = countdownClock.dataset.countdownTo;

      setInterval(jfUpdateClock, 1000);
    }
  }

  // Initialise in Editor, or on page load
  if (window.acf) {
    window.acf.addAction(
      "render_block_preview/type=ezpz/countdown",
      function () {
        initCountdown();
      }
    );
  } else {
    document.addEventListener("DOMContentLoaded", function () {
      initCountdown();
    });
  }
})(jQuery);
