self.onmessage = function(e) {
    try {
        const limit = e.data;
        const primes = [];

        function isPrime(num) {
            if (num < 2) return false;
            for (let i = 2; i <= Math.sqrt(num); i++) {
                if (num % i === 0) return false;
            }
            return true;
        }

        for (let i = 2; i <= limit; i++) {
            if (isPrime(i)) primes.push(i);
            if (primes.length >= 300) break;
        }

        self.postMessage(primes);

    } catch (error) {
        self.postMessage({ error: error.message });
    }
};
