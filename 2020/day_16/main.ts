const input: string[] = (await Deno.readTextFile(
  new URL(".", import.meta.url).pathname + "/input.txt",
)).split("\n\n");

class Field {
  constructor(
    public name: string,
    public low: [number, number],
    public high: [number, number],
  ) {}
}

function matchesField(field: Field, num: number): boolean {
  return (num >= field.low[0] && num <= field.low[1]) ||
    (num >= field.high[0] && num <= field.high[1]);
}

function matchesAtLeatOneField(fields: Field[], value: number): boolean {
  for (const field of fields) {
    if (matchesField(field, value)) {
      return true;
    }
  }

  return false;
}

const fields = input[0].trim().split("\n").filter((x) => x).map((line) => {
  const [, name, low1, low2, high1, high2] =
    /^([^:]+): (\d+)-(\d+) or (\d+)-(\d+)/.exec(line) || [];

  return new Field(
    name,
    [Number(low1), Number(low2)],
    [Number(high1), Number(high2)],
  );
});
const myTicket = input[1].trim().replace("your ticket:", "").split(",").map(
  Number,
);
const nearbyTickets = input[2].trim().replace("nearby tickets:", "").split("\n")
  .filter((x) => x).map((line) => line.split(",").map(Number));
const validTickets: number[][] = [myTicket];

let part1 = 0;
for (const nearbyTicket of nearbyTickets) {
  let valid = true;
  for (const num of nearbyTicket) {
    if (!matchesAtLeatOneField(fields, num)) {
      part1 += num;

      valid = false;
    }
  }

  if (valid) {
    validTickets.push(nearbyTicket);
  }
}

console.log(`Part 1: ${part1}`);

class FieldMatch {
  constructor(
    public field: string,
    public index: number,
  ) {}
}

let fieldMatches: FieldMatch[] = [];

for (let i = 0; i < myTicket.length; i++) {
  for (const field of fields) {
    let valid = true;

    for (const validTicket of validTickets) {
      if (!matchesField(field, validTicket[i])) {
        valid = false;

        break;
      }
    }

    if (valid) {
      fieldMatches.push(new FieldMatch(field.name, i));
    }
  }
}

while (fieldMatches.length > fields.length) {
  for (let i = 0; i < myTicket.length; i++) {
    const matches = fieldMatches.filter((fieldMatch) => fieldMatch.index === i);

    if (matches.length === 1) {
      const match = matches[0];

      fieldMatches = fieldMatches.filter((fieldMatch) =>
        fieldMatch.field === match.field
          ? fieldMatch.index === match.index
          : true
      );
    }
  }
}

const departureFields = fieldMatches.filter((fieldMatche) =>
  fieldMatche.field.startsWith("departure")
);

const part2 = departureFields.reduce(
  (result, departureField) => result *= myTicket[departureField.index],
  1,
);

console.log(`Part 2: ${part2}`);
