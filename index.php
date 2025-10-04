<!DOCTYPE html>
<html lang="en-GB">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Notebook — EU friendly</title>
  <style>
    :root{
      --max-width:900px;
    }
   body {
  font-family: Inter, Arial, sans-serif;
  margin: 0;
  padding: 20px;
  display: flex;
  justify-content: center;
  background: #FFC7E6;       /* soft pink background */
  color: #660033;            /* dark pink text for readability */
}

.container {
  width: 100%;
  max-width: var(--max-width);
  background: #FFC7F4;       /* lighter pink container */
  border-radius: 12px;
  box-shadow: 0 6px 18px rgba(10, 20, 40, 0.06);
  padding: 18px;
}

header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  margin-bottom: 12px;
}

h1 {
  font-size: 20px;
  margin: 0;
  color: #99004d;            /* dark pink heading */
}

.controls {
  display: flex;
  gap: 8px;
  align-items: center;
  flex-wrap: wrap;
}

textarea {
  width: 100%;
  min-height: 160px;
  max-height: 60vh;
  padding: 12px;
  box-sizing: border-box;
  font-size: 16px;
  line-height: 1.4;
  border: 1px solid #FF5CE9;  /* bright pink border */
  border-radius: 8px;
  resize: vertical;
  transition: font-size 0.12s;
  background: #FFF0F6;         /* very light pink background */
  color: #660033;              /* dark pink text */
}

.btn {
  padding: 8px 12px;
  border-radius: 8px;
  border: 1px solid #FF5CE9;   /* pink border */
  background: #FFC7F4;          /* light pink button */
  cursor: pointer;
  color: #660033;               /* dark pink text on buttons */
  transition: background-color 0.3s ease;
}

.btn:hover {
  background: #FF5CE9;          /* brighter pink on hover */
  color: white;
}

.small {
  font-size: 13px;
  padding: 6px 8px;
}

footer {
  margin-top: 10px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
  color: #660033;
  font-size: 13px;
}

.gdpr {
  position: fixed;
  left: 12px;
  right: 12px;
  bottom: 12px;
  max-width: var(--max-width);
  margin: 0 auto;
  background: #fff0f6;           /* very light pink */
  border-radius: 10px;
  box-shadow: 0 8px 24px rgba(12, 20, 40, 0.12);
  padding: 12px;
  display: flex;
  gap: 12px;
  align-items: center;
  justify-content: space-between;
  color: #660033;
}

.hidden {
  display: none !important;
}

.lang-toggle {
  border: 0;
  background: transparent;
  cursor: pointer;
  color: #ff33aa;              /* bright pink link */
  text-decoration: underline;
}

.meta {
  font-size: 13px;
  color: #660033;
}
</style>
</head>
<body>
  <div class="container" role="main">
    <header>
      <div>
        <h1 id="title">📓 My Notebook</h1>
        <div class="meta" id="subtitle">Autosave • Font controls • EU friendly</div>
      </div>

      <div class="controls" aria-label="controls">
        <button class="btn" id="btn-en">English</button>
        <button class="btn" id="btn-sq">Shqip</button>

        <button class="btn small" id="dec-font">A-</button>
        <button class="btn small" id="inc-font">A+</button>

        <button class="btn small" id="clear">Clear</button>
        <div id="date" class="meta"></div>
      </div>
    </header>

    <main>
      <textarea id="note" placeholder="Write your notes here..."></textarea>
    </main>

    <footer>
      <div class="meta" id="status">Saved locally in your browser (localStorage).</div>
      <div class="meta" id="hint">Use Ctrl + Scroll or Ctrl + / Ctrl - to change font size.</div>
    </footer>
  </div>

  <!-- GDPR cookie consent (simple) -->
  <div class="gdpr" id="gdpr">
    <div>
      <strong id="gdpr-title">Privacy & Cookies</strong>
      <div id="gdpr-text">This site stores your notes and preferences locally in your browser (localStorage). No data is sent to any server.</div>
    </div>
    <div style="display:flex; gap:8px;">
      <button class="btn" id="gdpr-accept">Accept</button>
      <button class="btn" id="gdpr-decline">Decline</button>
    </div>
  </div>

  <script>
    // -----------------------
    // Configuration & i18n
    // -----------------------
    const i18n = {
      en: {
        title: "📓 My Notebook",
        subtitle: "Autosave • Font controls • EU friendly",
        placeholder: "Write your notes here...",
        statusSaved: "Saved locally in your browser (localStorage).",
        hint: "Use Ctrl + Scroll or Ctrl + / Ctrl - to change font size.",
        gdprTitle: "Privacy & Cookies",
        gdprText: "This site stores your notes and preferences locally in your browser (localStorage). No data is sent to any server.",
        accept: "Accept",
        decline: "Decline",
        clearConfirm: "Clear all notes? This cannot be undone."
      },
      sq: {
        title: "📓 Fleta Ime",
        subtitle: "Ruajtje automatike • Kontroll i shkrimit • EU friendly",
        placeholder: "Shkruaj shënimet këtu...",
        statusSaved: "Ruhet lokalisht në shfletuesin tënd (localStorage).",
        hint: "Përdor Ctrl + Scroll ose Ctrl + / Ctrl - për madhësi të tekstit.",
        gdprTitle: "Privatësia & Cookies",
        gdprText: "Kjo faqe ruan shënimet dhe preferencat lokalisht (localStorage). Asgjë nuk dërgohet në server.",
        accept: "Prano",
        decline: "Refuzo",
        clearConfirm: "Të fshihet i gjithë teksti? Kjo nuk kthehet mbrapsht."
      }
    };

    // -----------------------
    // Elements & defaults
    // -----------------------
    const note = document.getElementById('note');
    const incBtn = document.getElementById('inc-font');
    const decBtn = document.getElementById('dec-font');
    const clearBtn = document.getElementById('clear');
    const statusEl = document.getElementById('status');
    const hintEl = document.getElementById('hint');
    const dateEl = document.getElementById('date');
    const gdprEl = document.getElementById('gdpr');
    const gdprTitle = document.getElementById('gdpr-title');
    const gdprText = document.getElementById('gdpr-text');
    const gdprAccept = document.getElementById('gdpr-accept');
    const gdprDecline = document.getElementById('gdpr-decline');
    const btnEN = document.getElementById('btn-en');
    const btnSQ = document.getElementById('btn-sq');

    // load preferences from localStorage
    let fontSize = parseInt(localStorage.getItem('fontSize')) || 16;
    let language = localStorage.getItem('uiLang') || 'en';
    let gdprAccepted = localStorage.getItem('gdprAccepted');

    // apply initial UI
    function applyLang(lang){
      const L = i18n[lang];
      document.getElementById('title').textContent = L.title;
      document.getElementById('subtitle').textContent = L.subtitle;
      note.placeholder = L.placeholder;
      statusEl.textContent = L.statusSaved;
      hintEl.textContent = L.hint;
      gdprTitle.textContent = L.gdprTitle;
      gdprText.textContent = L.gdprText;
      gdprAccept.textContent = L.accept;
      gdprDecline.textContent = L.decline;
      localStorage.setItem('uiLang', lang);
      language = lang;
    }

    applyLang(language);

    // show/hide gdpr banner depending on stored choice
    if (gdprAccepted === 'true') {
      gdprEl.classList.add('hidden');
    } else if (gdprAccepted === 'false') {
      // user declined: still hide banner but don't store notes automatically (we will still keep localStorage existence statement)
      gdprEl.classList.add('hidden');
    } else {
      // first visit: show banner
      gdprEl.classList.remove('hidden');
    }

    // -----------------------
    // Date display DD.MM.YYYY (EU)
    // -----------------------
    function formatEUDate(d = new Date()){
      const dd = String(d.getDate()).padStart(2,'0');
      const mm = String(d.getMonth() + 1).padStart(2,'0');
      const yyyy = d.getFullYear();
      return `${dd}.${mm}.${yyyy}`;
    }
    dateEl.textContent = formatEUDate();

    // -----------------------
    // Load saved note & font
    // -----------------------
    note.value = localStorage.getItem('noteText') || '';
    note.style.fontSize = fontSize + 'px';

    // Autosize: expand textarea height to fit content (simple)
    function autosize(){
      note.style.height = 'auto';
      note.style.height = (note.scrollHeight) + 'px';
    }
    autosize();

    // Save on input (respect GDPR accept)
    note.addEventListener('input', () => {
      autosize();
      if (localStorage.getItem('gdprAccepted') === 'true' || localStorage.getItem('gdprAccepted') === null) {
        localStorage.setItem('noteText', note.value);
      } else {
        // if declined we avoid updating noteText (but still allow in-memory typing)
      }
      // quick status feedback
      statusEl.textContent = (language === 'sq') ? 'Ruhet lokalisht...' : 'Saved locally...';
      setTimeout(()=> {
        statusEl.textContent = i18n[language].statusSaved;
      }, 900);
    });

    // Font controls
    function updateFont(size){
      fontSize = size;
      note.style.fontSize = fontSize + 'px';
      localStorage.setItem('fontSize', fontSize);
    }
    incBtn.addEventListener('click', () => updateFont(fontSize + 2));
    decBtn.addEventListener('click', () => {
      if (fontSize > 8) updateFont(fontSize - 2);
    });

    // Clear button
    clearBtn.addEventListener('click', () => {
      const conf = confirm(i18n[language].clearConfirm);
      if (!conf) return;
      note.value = '';
      autosize();
      localStorage.removeItem('noteText');
    });

    // CTRL + wheel to change font size on note
    note.addEventListener('wheel', (e) => {
      if (e.ctrlKey) {
        e.preventDefault();
        if (e.deltaY < 0) updateFont(fontSize + 2);
        else updateFont(Math.max(8, fontSize - 2));
      }
    }, { passive: false });

    // CTRL + / CTRL - globally for font size
    document.addEventListener('keydown', (e) => {
      if (e.ctrlKey && (e.key === '+' || e.key === '=')) {
        e.preventDefault();
        updateFont(fontSize + 2);
      }
      if (e.ctrlKey && (e.key === '-' || e.key === '_')) {
        e.preventDefault();
        updateFont(Math.max(8, fontSize - 2));
      }
    });

    // GDPR accept/decline
    gdprAccept.addEventListener('click', () => {
      localStorage.setItem('gdprAccepted','true');
      gdprEl.classList.add('hidden');
      // on accept we ensure note stored
      localStorage.setItem('noteText', note.value || '');
    });
    gdprDecline.addEventListener('click', () => {
      localStorage.setItem('gdprAccepted','false');
      gdprEl.classList.add('hidden');
      // optionally remove previously stored note if user declines
      // localStorage.removeItem('noteText');
    });

    // language buttons
    btnEN.addEventListener('click', () => applyLang('en'));
    btnSQ.addEventListener('click', () => applyLang('sq'));

    // accessibility: expose status via aria-live (simple)
    statusEl.setAttribute('role','status');

    // small safety: if localStorage not available, disable save attempts
    try {
      const testKey = '__test_ls';
      localStorage.setItem(testKey, '1');
      localStorage.removeItem(testKey);
    } catch(err) {
      console.warn('localStorage not available:', err);
      statusEl.textContent = (language === 'sq') ? 'localStorage nuk është i disponueshëm' : 'localStorage not available';
    }

    // final: set initial language UI
    applyLang(language);

    // helpful debug: show console instructions when something fails
    // (users can open DevTools to see errors)
  </script>
</body>
</html>
