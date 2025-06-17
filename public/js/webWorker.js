self.onmessage = function (e) {
    try {
        const limit = parseInt(e.data);

        if (isNaN(limit) || limit < 2) {
            throw new Error("Límite inválido para el cálculo de números primos.");
        }

        const primes = [];
        for (let num = 2; num <= limit; num++) {
            let isPrime = true;
            for (let i = 2; i <= Math.sqrt(num); i++) {
                if (num % i === 0) {
                    isPrime = false;
                    break;
                }
            }
            if (isPrime) {
                primes.push(num);
                if (primes.length >= 300) break; // Solo queremos los primeros 300
            }
        }

        self.postMessage({ success: true, primes });
    } catch (error) {
        self.postMessage({ success: false, message: error.message });
    }
};