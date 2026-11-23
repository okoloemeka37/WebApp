
let currentAudio = null;
let currentButton = null;

let audioContext = null;
let analyser = null;
let animationFrame = null;

function setIcon(button, isPlaying) {
    button.innerHTML = isPlaying
        ? '<i class="fa-solid fa-pause text-sm"></i>'
        : '<i class="fa-solid fa-play text-sm"></i>';
}

function stopWaveform() {
    cancelAnimationFrame(animationFrame);
    animationFrame = null;
}

function resetWaveform(parentDiv) {
    const bars = parentDiv.querySelectorAll(".freqHolder span");

    bars.forEach(bar => {
        bar.style.height = "";
        bar.style.backgroundColor = "";
    });
}

function HandleFreq(parentDiv) {
    stopWaveform();

    const freqHolder = parentDiv.querySelector(".freqHolder");
    if (!freqHolder || !analyser) return;

    const bars = freqHolder.children;
    const dataArray = new Uint8Array(analyser.frequencyBinCount);

    function animate() {
        analyser.getByteFrequencyData(dataArray);

        Array.from(bars).forEach((bar, index) => {
            const dataIndex = Math.floor(
                index * dataArray.length / bars.length
            );

            const value = dataArray[dataIndex];

            const height = Math.max(4, (value / 255) * 30);

            bar.style.height = `${height}px`;
            bar.style.backgroundColor =
                value > 20 ? "#f97316" : "#737373";
        });

        animationFrame = requestAnimationFrame(animate);
    }

    animate();
}

export default async function VoiceNote(button) {

    const parentDiv = button.parentElement;

    // Same voice note: pause or resume
    if (currentAudio && currentButton === button) {

        if (currentAudio.paused) {
            try {
                await audioContext.resume();
                await currentAudio.play();
            } catch (error) {
                console.error("Audio playback failed:", error);
            }
        } else {
            currentAudio.pause();
        }

        return;
    }

    // Stop the previous voice note
    if (currentAudio) {
        const oldParent = currentButton.parentElement;

        currentAudio.pause();
        currentAudio.currentTime = 0;

        setIcon(currentButton, false);
        stopWaveform();
        resetWaveform(oldParent);

        const oldSpeaker = oldParent.querySelector(".VnSpeaker");

        if (oldSpeaker) {
            oldSpeaker.innerHTML =
                '<i class="fa-solid fa-volume-xmark text-orange-400"></i>';
        }
    }

    // Create new audio
    const base64 = button.dataset.audio;
    const audio = new Audio(`data:audio/ogg;base64,${base64}`);

    // Create audio context only once
    if (!audioContext) {
        audioContext = new AudioContext();
    }

    // Each audio element needs its own MediaElementSource
    const source = audioContext.createMediaElementSource(audio);

    analyser = audioContext.createAnalyser();
    analyser.fftSize = 256;

    source.connect(analyser);
    analyser.connect(audioContext.destination);

    currentAudio = audio;
    currentButton = button;

    const speaker = parentDiv.querySelector(".VnSpeaker");

    let counter=1;
    let ctIn=null
audio.addEventListener("play", async () => {
    setIcon(button, true);

    if (audioContext.state === "suspended") {
        await audioContext.resume();
    }

    HandleFreq(parentDiv);

    if (speaker) {
        speaker.innerHTML =
            '<i class="fa-solid fa-volume-high text-xs text-orange-400"></i>';
    }
});

audio.addEventListener("timeupdate", () => {
    const timer = parentDiv.querySelector(".timer");

    if (timer) {
        timer.textContent = formatTime(audio.currentTime);
    }
});

   audio.addEventListener("pause", () => {
    setIcon(button, false);

    stopWaveform();

    if (speaker) {
        speaker.innerHTML =
            '<i class="fa-solid fa-volume-xmark text-orange-400"></i>';
    }
});

audio.addEventListener("ended", () => {
    setIcon(button, false);

    stopWaveform();
    resetWaveform(parentDiv);

    const timer = parentDiv.querySelector(".timer");

    if (timer) {
        timer.textContent = formatTime(audio.duration);
    }

    if (speaker) {
        speaker.innerHTML =
            '<i class="fa-solid fa-volume-xmark text-neutral-400"></i>';
    }

    if (currentAudio === audio) {
        currentAudio = null;
        currentButton = null;
    }
});

    try {
        await audioContext.resume();
        await audio.play();
    } catch (error) {
        console.error("Audio playback failed:", error);
    }
}


function formatTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);

    return `${minutes}:${String(secs).padStart(2, '0')}`;
}