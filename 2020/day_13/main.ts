const input: string[] = (await Deno.readTextFile(
  new URL(".", import.meta.url).pathname + "/input.txt",
)).split("\n").filter((x) => x);

const timestamp = Number(input[0]);

function part1() {
  const earliestBuses = input[1].split(",").filter((x) => x !== "x").map((x) =>
    Number(x)
  ).map((bus) => {
    let departure = 0;

    while (departure < timestamp) {
      departure += bus;
    }

    return {
      id: bus,
      departure,
    };
  });

  const [bus] = earliestBuses.sort((a, b) => a.departure - b.departure);

  return (bus.departure - timestamp) * bus.id;
}

console.log(`Part 1: ${part1()}`);

function part2() {
  const buses = input[1].split(",").map((bus, index) => ({
    id: Number(bus),
    index,
  })).filter((bus) => !Number.isNaN(bus.id));

  let multiplier = buses[0].id;
  let i = 0;
  let nextBusIndex = 1;

  while (true) {
    if (buses.every((bus) => (i + bus.index) % bus.id === 0)) {
      return i;
    }

    const nextBus = buses[nextBusIndex];

    if ((i + nextBus.index) % nextBus.id === 0) {
      multiplier *= nextBus.id;
      nextBusIndex++;
    }

    i += multiplier;
  }
}

console.log(`Part 2: ${part2()}`);
