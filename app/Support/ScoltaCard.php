<?php

namespace App\Support;

/**
 * Builds the per-item metadata the search result cards paint.
 *
 * The card is text-forward and carries no thumbnail. This reference has no
 * per-item image and no column that could hold one: the migrations for
 * conditions, medications, procedures, anatomies and articles define none, and
 * public/images holds only site chrome. A stock stethoscope photograph beside
 * "Decompression Sickness (Lunar Variant)" would be decoration pretending to be
 * information. What a clinical search wants to know before the click is what
 * kind of reference a hit is, which system or drug class it belongs to, and
 * whether it is urgent, so that is what the card says.
 *
 * Badges are [kind, label] pairs rather than bare strings, because two kinds
 * are drawn differently: an urgency reads in the theme's alert colours where a
 * content type or a body system reads as a neutral chip. Deciding that here
 * rather than in the renderer means a body system named "Critical" could not
 * one day paint itself red.
 *
 * Metadata costs only the bytes of its own keys in each fragment, unlike
 * sortable, which writes a corpus-wide pf_meta entry. The keys "title" and
 * "date" are deliberately avoided: they lose to the built-in values on a
 * collision.
 */
final class ScoltaCard
{
    /**
     * How many badges a search result card paints.
     *
     * Three is what fits on one row under a title at the search page's content
     * width without wrapping.
     */
    public const BADGE_LIMIT = 3;

    /**
     * Encodes an ordered badge list into the item's metadata array.
     *
     * Candidates are given most-important-first and trimmed to the limit.
     * Duplicates are dropped case-insensitively, because the vocabularies
     * overlap: a procedure in the "emergency" category would otherwise carry
     * an Emergency urgency chip and an Emergency type chip side by side, which
     * spends a slot to say one thing twice.
     *
     * JSON rather than a delimited string: these values are free text from the
     * database, so there is no separator a future one provably cannot contain.
     *
     * @param  array<int, array{0: string, 1: string|null}>  $candidates  Ordered [kind, label] pairs.
     * @return array<string, string>  Metadata keys to merge, or an empty array.
     */
    public static function metadata(array $candidates): array
    {
        $badges = [];
        $seen = [];

        foreach ($candidates as $candidate) {
            if (count($badges) >= self::BADGE_LIMIT) {
                break;
            }
            [$kind, $label] = [$candidate[0] ?? '', $candidate[1] ?? null];
            $label = is_string($label) ? trim($label) : '';
            if ($kind === '' || $label === '') {
                continue;
            }
            $fold = mb_strtolower($label);
            if (isset($seen[$fold])) {
                continue;
            }
            $seen[$fold] = true;
            $badges[] = [$kind, self::label($label)];
        }

        if ($badges === []) {
            return [];
        }

        $encoded = json_encode($badges);

        return is_string($encoded) ? ['badges' => $encoded] : [];
    }

    /**
     * Presents a stored value the way a reader expects to see it.
     *
     * These columns hold machine tokens — "first_aid", "musculoskeletal",
     * "multi-system" — which are the right thing in the database and the wrong
     * thing on a card. The facet panel shows them as stored, so this is the one
     * place in the rollout where a badge's text deliberately differs from its
     * facet value; it differs only in case and word separator, which is a
     * presentation difference rather than a different value.
     */
    private static function label(string $value): string
    {
        return ucwords(str_replace(['_', '-'], ' ', $value));
    }
}
