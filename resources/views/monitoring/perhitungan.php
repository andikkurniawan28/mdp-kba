<script>
function setupFlowAutoCalculation() {
    const paramIds = [2, 3, 4, 5, 6]; // Input manual
    const targetIds = [7, 8, 9, 10, 11]; // Output otomatis
    const persenIds = [12, 13, 14]; // Output % tebu
    const label = ['nmp', 'nmg', 'imb', 'd1', 'd2'];

    const param1 = document.getElementById('param1'); // Tebu tergiling
    const persenOutputs = [
        document.getElementById('param12'), // NMP % tebu
        document.getElementById('param13'), // NMG % tebu
        document.getElementById('param14'), // IMB % tebu
    ];

    paramIds.forEach((paramId, index) => {
        const input = document.getElementById(`param${paramId}`);
        const output = document.getElementById(`param${targetIds[index]}`);
        const lastValElem = document.getElementById(`totalizer_${label[index]}_terakhir`);
        const lastVal = parseFloat(lastValElem?.value ?? 0) || 0;

        if (input && output) {
            input.addEventListener('input', () => {
                const currentVal = parseFloat(input.value);
                const tebuVal = parseFloat(param1?.value ?? 0);

                if (!isNaN(currentVal)) {
                    const result = (currentVal - lastVal).toFixed(2);
                    output.value = result;

                    // Hitung % tebu jika applicable
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

    // Update % tebu ketika param1 diubah
    if (param1) {
        param1.addEventListener('input', () => {
            const tebuVal = parseFloat(param1.value);
            if (!isNaN(tebuVal) && tebuVal !== 0) {
                [7, 8, 9].forEach((flowId, i) => {
                    const flowVal = parseFloat(document.getElementById(`param${flowId}`)?.value ?? 0);
                    if (!isNaN(flowVal)) {
                        const persenVal = ((flowVal / tebuVal) * 1000).toFixed(2);
                        persenOutputs[i].value = persenVal;
                    } else {
                        persenOutputs[i].value = '';
                    }
                });
            } else {
                persenOutputs.forEach(p => p.value = '');
            }
        });
    }
}

// Panggil saat DOM siap
document.addEventListener('DOMContentLoaded', setupFlowAutoCalculation);
</script>
