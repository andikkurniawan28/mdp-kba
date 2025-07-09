<script>
    function isVisible(elem) {
        return elem && elem.offsetParent !== null;
    }

    function setupFlowAutoCalculation() {
        const paramIds = [2, 3, 4, 5, 6]; // Input manual
        const targetIds = [7, 8, 9, 10, 11]; // Output otomatis
        const persenIds = [12, 13, 14]; // Output % tebu
        const label = ['nmp', 'nmg', 'imb', 'd1', 'd2'];

        const param1 = document.getElementById('param1'); // Tebu tergiling
        const persenOutputs = persenIds.map(id => document.getElementById(`param${id}`));

        paramIds.forEach((paramId, index) => {
            const input = document.getElementById(`param${paramId}`);
            const output = document.getElementById(`param${targetIds[index]}`);
            const lastValElem = document.getElementById(`totalizer_${label[index]}_terakhir`);
            const lastVal = parseFloat(lastValElem?.value ?? 0) || 0;

            if (input && output) {
                input.addEventListener('input', () => {
                    const parentDiv = input.closest('.input-parameter');

                    // Hentikan jika tidak terlihat atau disembunyikan
                    if (!isVisible(input) || !isVisible(output) || parentDiv?.classList.contains('visually-hidden')) {
                        output.value = '';
                        if (index <= 2) persenOutputs[index].value = '';
                        return;
                    }

                    const currentVal = parseFloat(input.value);
                    const tebuVal = parseFloat(param1?.value ?? 0);

                    if (!isNaN(currentVal)) {
                        const result = (currentVal - lastVal).toFixed(2);
                        output.value = result;

                        if (index <= 2 && !isNaN(tebuVal) && tebuVal !== 0) {
                            const persenVal = ((result / tebuVal) * 1000).toFixed(2);
                            persenOutputs[index].value = persenVal;
                        } else if (index <= 2) {
                            persenOutputs[index].value = '';
                        }
                    } else {
                        output.value = '';
                        if (index <= 2) persenOutputs[index].value = '';
                    }
                });
            }
        });

        // Update persen jika param1 berubah
        if (param1) {
            param1.addEventListener('input', () => {
                const tebuVal = parseFloat(param1.value);
                if (!isNaN(tebuVal) && tebuVal !== 0) {
                    [7, 8, 9].forEach((flowId, i) => {
                        const flowInput = document.getElementById(`param${flowId}`);
                        const persenOutput = persenOutputs[i];
                        const parentDiv = flowInput.closest('.input-parameter');

                        if (isVisible(flowInput) && isVisible(persenOutput) && !parentDiv?.classList.contains('visually-hidden')) {
                            const flowVal = parseFloat(flowInput?.value ?? 0);
                            if (!isNaN(flowVal)) {
                                const persenVal = ((flowVal / tebuVal) * 1000).toFixed(2);
                                persenOutput.value = persenVal;
                            } else {
                                persenOutput.value = '';
                            }
                        } else {
                            persenOutput.value = '';
                        }
                    });
                } else {
                    persenOutputs.forEach(p => p.value = '');
                }
            });
        }
    }

    function setupHKCalculation() {
        const paramBrix = document.getElementById('param15');
        const paramPol = document.getElementById('param16');
        const paramHK = document.getElementById('param18');

        function calculateHK() {
            const parentDiv = paramHK?.closest('.input-parameter');
            if (!isVisible(paramHK) || parentDiv?.classList.contains('visually-hidden')) {
                if (paramHK) paramHK.value = '';
                return;
            }

            const brix = parseFloat(paramBrix?.value ?? '');
            const pol = parseFloat(paramPol?.value ?? '');

            if (!isNaN(brix) && brix !== 0 && !isNaN(pol)) {
                const hk = (pol / brix * 100).toFixed(2);
                paramHK.value = hk;
            } else {
                paramHK.value = '';
            }
        }

        if (paramBrix && paramPol) {
            paramBrix.addEventListener('input', calculateHK);
            paramPol.addEventListener('input', calculateHK);
        }
    }

    function setupRendemenCalculation() {
        const paramBrix = document.getElementById('param15');
        const paramPol = document.getElementById('param16');
        const paramRendemen = document.getElementById('param19');

        function calculateRendemen() {
            const parentDiv = paramRendemen?.closest('.input-parameter');
            if (!isVisible(paramRendemen) || parentDiv?.classList.contains('visually-hidden')) {
                if (paramRendemen) paramRendemen.value = '';
                return;
            }

            const brix = parseFloat(paramBrix?.value ?? '');
            const pol = parseFloat(paramPol?.value ?? '');

            if (!isNaN(brix) && !isNaN(pol)) {
                const rendemen = (0.7 * (pol - 0.4 * (brix - pol))).toFixed(2);
                paramRendemen.value = rendemen;
            } else {
                paramRendemen.value = '';
            }
        }

        if (paramBrix && paramPol) {
            paramBrix.addEventListener('input', calculateRendemen);
            paramPol.addEventListener('input', calculateRendemen);
        }
    }

    // Inisialisasi saat DOM siap
    document.addEventListener('DOMContentLoaded', function() {
        setupFlowAutoCalculation();
        setupHKCalculation();
        setupRendemenCalculation();
    });
</script>

<script>
    // Mencegah submit field yang tidak aktif (tersembunyi)
    $('form').on('submit', function() {
        $('.input-parameter.visually-hidden').each(function() {
            $(this).find('input, select, textarea').prop('disabled', true);
        });
    });
</script>

