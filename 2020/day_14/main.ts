const input: string[] = (await Deno.readTextFile(
  new URL(".", import.meta.url).pathname + "/input.txt",
)).split("\n").filter((x) => x);

function num2bin(number: number): string {
  const bin = number.toString(2);

  return bin.padStart(36, "0");
}

function applyMask(mask: string, bin: string): number {
  const raw = [...bin];

  mask.split("").forEach((value, index) => {
    if (value !== "X") {
      raw[index] = value;
    }
  });

  return parseInt(raw.join(""), 2);
}

function getMemoryAddresses(mask: string, bin: string) {
  const raw = [...bin];
  let addresses: string[][] = [["0"]];

  mask.split("").forEach((value, index) => {
    if (value !== "0") {
      raw[index] = value;
    }
  });

  for (const char of raw) {
    if (char !== "X") {
      addresses.forEach((address) => address.push(char));
    } else {
      addresses = addresses.flatMap((address) => {
        const one = address.slice();
        const zero = address.slice();

        one.push("1");
        zero.push("0");

        return [zero, one];
      });
    }
  }

  return addresses.map((address) => parseInt(address.join(""), 2));
}

function part1() {
  const memory = new Map<number, number>();
  let currentMask = "";

  for (const line of input) {
    const [start, end] = line.split(" = ");

    if (start === "mask") {
      currentMask = end;
    } else {
      const memAddress = Number(start.trim().replace(/(mem\[|\])/g, ""));
      const value = Number(end);

      memory.set(memAddress, applyMask(currentMask, num2bin(value)));
    }
  }

  return [...memory.values()].reduce((sum, current) => sum += current, 0);
}

function part2() {
  const memory = new Map<number, number>();
  let currentMask = "";

  for (const line of input) {
    const [start, end] = line.split(" = ");

    if (start === "mask") {
      currentMask = end;
    } else {
      const memAddress = Number(start.trim().replace(/(mem\[|\])/g, ""));
      const value = Number(end);
      const addresses = getMemoryAddresses(currentMask, num2bin(memAddress));

      addresses.forEach((address) => {
        memory.set(address, value);
      });
    }
  }

  return [...memory.values()].reduce((sum, current) => sum += current, 0);
}

console.log(`Part 1: ${part1()}`);
console.log(`Part 2: ${part2()}`);
