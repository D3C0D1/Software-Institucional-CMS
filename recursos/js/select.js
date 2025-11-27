function loadPrograma(value) {
    const iframe = document.getElementById("iframe");

    const urls = {
        1: "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/1921576191",
        2: "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/1921794011",
        3: "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/1921629227",
        4: "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/1921627367",
        5: "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/1921623783",
        6: "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/1921621879",
        7: "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/1921621291",
        8: "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/1921620407",
        9: "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/1921618851",
        10: "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/1921616859",
        11: "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/1921615951",
        12: "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/1921611739",
        13: "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/1921602203",
        14: "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/1925706467",
        15: "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/1923561387",
        16: "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/1923511763",
        17: "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/1921625147",
        18: "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/1921613659",
        19: "https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/playlists/1921574067"
    };
    

    if (urls[value]) {
        iframe.src = urls[value];
    } else {
        iframe.src = "";
    }
}
