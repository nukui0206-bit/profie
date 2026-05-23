@props([
    'name' => 'birthdate',
    'value' => null,
    'minAgeYears' => 13,
    'maxAgeYears' => 100,
])

@php
    $today = \Illuminate\Support\Carbon::today();
    $maxYear = $today->copy()->subYears($minAgeYears)->year;
    $minYear = $today->copy()->subYears($maxAgeYears)->year;

    $selectedYear = '';
    $selectedMonth = '';
    $selectedDay = '';

    // old() or 既存値から年/月/日を取り出す
    $oldYear = old("{$name}_year");
    $oldMonth = old("{$name}_month");
    $oldDay = old("{$name}_day");
    if ($oldYear || $oldMonth || $oldDay) {
        $selectedYear = $oldYear;
        $selectedMonth = $oldMonth;
        $selectedDay = $oldDay;
    } elseif ($value) {
        try {
            $dt = \Illuminate\Support\Carbon::parse($value);
            $selectedYear = (string) $dt->year;
            $selectedMonth = (string) $dt->month;
            $selectedDay = (string) $dt->day;
        } catch (\Throwable $e) {
            // ignore parse errors
        }
    }

    $uid = 'bd-' . \Illuminate\Support\Str::random(6);
@endphp

<div class="row g-2" id="{{ $uid }}">
    <div class="col-5">
        <select name="{{ $name }}_year" class="form-select" required aria-label="年">
            <option value="">年</option>
            @for ($y = $maxYear; $y >= $minYear; $y--)
                <option value="{{ $y }}" @selected((string) $selectedYear === (string) $y)>{{ $y }}</option>
            @endfor
        </select>
    </div>
    <div class="col-3">
        <select name="{{ $name }}_month" class="form-select" required aria-label="月">
            <option value="">月</option>
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" @selected((string) $selectedMonth === (string) $m)>{{ $m }}</option>
            @endfor
        </select>
    </div>
    <div class="col-4">
        <select name="{{ $name }}_day" class="form-select" required aria-label="日">
            <option value="">日</option>
            @for ($d = 1; $d <= 31; $d++)
                <option value="{{ $d }}" @selected((string) $selectedDay === (string) $d)>{{ $d }}</option>
            @endfor
        </select>
    </div>
</div>

<input type="hidden" name="{{ $name }}" id="{{ $uid }}-hidden" value="{{ old($name) }}">

<script>
    (function () {
        const root = document.getElementById('{{ $uid }}');
        const hidden = document.getElementById('{{ $uid }}-hidden');
        if (!root || !hidden) return;
        const ys = root.querySelector('select[name="{{ $name }}_year"]');
        const ms = root.querySelector('select[name="{{ $name }}_month"]');
        const ds = root.querySelector('select[name="{{ $name }}_day"]');
        const pad = (n) => String(n).padStart(2, '0');
        const sync = () => {
            if (ys.value && ms.value && ds.value) {
                hidden.value = `${ys.value}-${pad(ms.value)}-${pad(ds.value)}`;
            } else {
                hidden.value = '';
            }
        };
        [ys, ms, ds].forEach((el) => el.addEventListener('change', sync));
        sync(); // 初期値で hidden を更新（再表示時）
    })();
</script>
