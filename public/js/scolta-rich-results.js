/**
 * Search result cards for Medical On The Moon.
 *
 * Registers two Scolta renderers: a result renderer that puts what kind of
 * reference a hit is, which system or drug class it belongs to and how urgent
 * it is under the title, and a suggestion renderer that puts the reference
 * type on each search-as-you-type row. Both read from the search index — the
 * labels ride along in the fragment's meta map, put there by
 * App\Support\ScoltaCard through each model's toSearchableContent() — so
 * neither costs a per-result server call.
 *
 * There is no thumbnail here, on purpose. This reference has no per-item image
 * and no column that could hold one: the migrations for conditions,
 * medications, procedures, anatomies and articles define none, and
 * public/images holds only site chrome. A stock stethoscope photograph beside
 * "Decompression Sickness (Lunar Variant)" would be decoration pretending to
 * be information.
 *
 * Load order matters. scolta.js defines window.Scolta when it executes and
 * calls Scolta.init() on DOMContentLoaded. This file is pushed onto the
 * layout's script stack, which renders after the search component's own
 * <script defer>, and defer preserves document order while still running
 * everything before DOMContentLoaded — exactly the window the renderers have
 * to register in.
 */
(function (global) {
  'use strict';

  if (!global.Scolta || typeof global.Scolta.setResultRenderer !== 'function') {
    // A bundle without the render seam is not something to work around here.
    console.warn('[mottm] Scolta.setResultRenderer unavailable; leaving the built-in card in place.');
    return;
  }

  var ENTITIES = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#39;',
  };

  function escapeHtml(value) {
    return String(value === null || value === undefined ? '' : value)
      .replace(/[&<>"']/g, function (c) { return ENTITIES[c]; });
  }

  /**
   * How many badges a card paints. Mirrors the indexer's own cap, which is
   * what actually bounds the string; this is the client-side belt to it.
   */
  var BADGE_LIMIT = 3;

  /**
   * Badge kinds the stylesheet has a treatment for.
   *
   * An unknown kind falls back to the neutral chip rather than emitting a
   * class nothing styles, so a kind added to the indexer later degrades
   * instead of rendering unstyled.
   */
  var KINDS = { urgency: 1, type: 1, system: 1 };

  /**
   * Urgency levels the stylesheet colours, and the band each falls in.
   *
   * Two vocabularies land in this one badge — a condition's severity and a
   * procedure's risk level — and they use different words for the same three
   * bands. Mapping them here keeps one colour scale across the whole result
   * list, so "Critical" and "High" do not read as unrelated states.
   *
   * Checked against a fixed table rather than interpolated, so a level the
   * database grows later cannot inject a class name.
   */
  var URGENCY = {
    emergency: 'red',
    critical: 'red',
    severe: 'amber',
    high: 'amber',
    moderate: 'neutral',
    medium: 'neutral',
    minor: 'green',
    low: 'green',
  };

  /**
   * Renders a result's badges.
   *
   * data.meta.badges is raw index data: a JSON-encoded array of [kind, label]
   * pairs, already ordered, deduplicated and capped by App\Support\ScoltaCard.
   * Pairs rather than bare strings so urgency can be coloured without the
   * renderer guessing from the text — a body system named "Critical" could not
   * one day paint itself red.
   *
   * Anything that does not parse into an array of pairs counts as no badges.
   * An item without them simply shows none, rather than a broken card.
   */
  function badges(encoded) {
    if (!encoded) {
      return '';
    }
    var pairs;
    try {
      pairs = JSON.parse(encoded);
    } catch (e) {
      return '';
    }
    if (!Array.isArray(pairs)) {
      return '';
    }
    var out = '';
    for (var i = 0; i < pairs.length && i < BADGE_LIMIT; i++) {
      var pair = pairs[i];
      if (!Array.isArray(pair) || pair.length < 2) {
        continue;
      }
      var kind = String(pair[0] || '');
      var label = String(pair[1] === null || pair[1] === undefined ? '' : pair[1]).trim();
      if (label === '') {
        continue;
      }
      var cls = 'mottm-result__badge';
      if (KINDS[kind]) {
        cls += ' mottm-result__badge--' + kind;
      }
      if (kind === 'urgency') {
        var band = URGENCY[label.toLowerCase()];
        if (band) {
          cls += ' mottm-result__badge--' + band;
        }
      }
      out += '<span class="' + cls + '">' + escapeHtml(label) + '</span>';
    }
    return out;
  }

  /**
   * Renders one result.
   *
   * Escaping: every ctx value used here ends in Html, Attr or Text, or is
   * safeUrl, so Scolta has already escaped it exactly as its own card would.
   * Everything read from data.meta is raw index data and is escaped here.
   * ctx.query and ctx.highlightTerms are raw and never reach the markup.
   *
   * An item with no badges gets this same card with no meta row, never
   * Scolta's built-in one. Mixing two card designs down a single result list
   * reads as a broken page rather than a designed fallback.
   */
  global.Scolta.setResultRenderer(function (data, ctx) {
    var meta = (data && data.meta) || {};
    var badgeHtml = badges(meta.badges);

    var metaRow = badgeHtml === '' ? ''
      : '<div class="mottm-result__meta">' + badgeHtml + '</div>';

    // target/rel match the built-in card: within one result list, no card may
    // open differently from its neighbour.
    return '<div class="scolta-result-card mottm-result">'
      + '<a class="scolta-result-title mottm-result__title" href="' + ctx.safeUrl + '"'
      + ' target="_blank" rel="noopener" title="' + ctx.titleAttr + '">' + ctx.titleHtml + '</a>'
      + metaRow
      + '<div class="scolta-result-excerpt mottm-result__excerpt">' + ctx.excerptHtml + '</div>'
      + '</div>';
  });

  // Behind its own guard rather than the file-level one: this seam landed
  // after setResultRenderer, so a bundle old enough to lack it still gets the
  // cards above, and the dropdown degrades to the themed but untagged rows
  // instead of throwing.
  if (typeof global.Scolta.setSuggestionRenderer !== 'function') {
    return;
  }

  /**
   * Renders one search-as-you-type suggestion row.
   *
   * Returns the row's INNER markup only. The option element around it is the
   * bundle's, and it is what carries the combobox contract — role="option",
   * the stable id the input's aria-activedescendant points at, aria-selected,
   * the data-scolta-sayt-index the keyboard and click handlers dispatch on,
   * and the href in navigate mode. None of that is restated here, because a
   * renderer cannot break by omission what it never writes.
   *
   * Where a demo with pictures puts a thumbnail, this row puts the reference
   * type, read off the first badge. On this corpus that is the fact worth the
   * width: a query like "hypoxia" matches the condition, the medications for
   * it, the procedure, the anatomy entry and a review article, and which one
   * you want depends entirely on why you are asking. Rows with no type still
   * reserve the column, so the titles stay aligned down the list.
   *
   * Escaping: ctx.titleHtml and ctx.excerptHtml arrive pre-escaped, escaped
   * exactly as the built-in row escapes them. suggestion.meta.* is raw index
   * data and is escaped here. ctx.query is raw and never reaches the markup.
   *
   * A recent search is handed back to the built-in row by returning null: it
   * has no fragment, no type and nothing to add, and the built-in row is
   * already the themed glyph treatment this dropdown wants for history.
   */
  function referenceType(encoded) {
    if (!encoded) {
      return '';
    }
    var pairs;
    try {
      pairs = JSON.parse(encoded);
    } catch (e) {
      return '';
    }
    if (!Array.isArray(pairs)) {
      return '';
    }
    for (var i = 0; i < pairs.length; i++) {
      if (Array.isArray(pairs[i]) && pairs[i][0] === 'type') {
        return String(pairs[i][1] === null || pairs[i][1] === undefined ? '' : pairs[i][1]).trim();
      }
    }
    return '';
  }

  global.Scolta.setSuggestionRenderer(function (suggestion, ctx) {
    if (!suggestion || suggestion.type !== 'title') {
      return null;
    }

    var type = referenceType((suggestion.meta || {}).badges);

    // Decorative and aria-hidden: an option's accessible name is computed from
    // its contents, so this would otherwise be announced in front of the title
    // it qualifies — "Conditions, Decompression Sickness". The title names the
    // row.
    var tag = type === ''
      ? '<span class="mottm-sayt__type mottm-sayt__type--empty" aria-hidden="true"></span>'
      : '<span class="mottm-sayt__type" aria-hidden="true">' + escapeHtml(type) + '</span>';

    return '<span class="mottm-sayt">'
      + tag
      // Both classes on purpose. The scolta-* one carries the look the theme
      // already gives a suggestion's title and excerpt, so a title row and a
      // recent-search row stay typographically identical; the mottm-* one adds
      // only the layout this row needs. Two classes at the same specificity,
      // resolved by source order, rather than a nested selector.
      + '<span class="mottm-sayt__text">'
      + '<span class="scolta-sayt-title mottm-sayt__title">' + ctx.titleHtml + '</span>'
      + (ctx.excerptHtml
        ? '<span class="scolta-sayt-excerpt mottm-sayt__excerpt">' + ctx.excerptHtml + '</span>'
        : '')
      + '</span>'
      + '</span>';
  });

})(window);
