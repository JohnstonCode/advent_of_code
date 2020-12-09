const preamble: number[] = (await Deno.readTextFile(
  new URL(".", import.meta.url).pathname + "/input.txt",
)).split("\n").filter((x) => x).map(Number);

function arrayHasSum(numbers: number[], toSum: number): boolean {
  for (let i = 0; i < numbers.length - 1; i++) {
    for (let j = i + 1; j < numbers.length; j++) {
      if ((numbers[i] + numbers[j]) === toSum) {
        return true;
      }
    }
  }

  return false;
}

function notSumable(preamble: number[], length: number) {
  for (let i = length; i < preamble.length; i++) {
    const sum = preamble[i];

    if (!arrayHasSum(preamble.slice(i - length, i), sum)) {
      return sum;
    }
  }

  return 0;
}

const invalidNumber = notSumable(preamble, 25);

console.log(`Part 1: ${invalidNumber}`);

loop:
for (let i = 0; i < preamble.length; i++) {
  let currentIndex = i;
  let total = 0;

  while (currentIndex < preamble.length) {
    if (total > invalidNumber) {
      break;
    }

    if (total === invalidNumber) {
      const range = preamble.slice(i, currentIndex);
      const res = Math.min(...range) + Math.max(...range);
      console.log(`Part 2: ${res}`);

      break loop;
    }

    total += preamble[currentIndex];

    currentIndex++;
  }
}
