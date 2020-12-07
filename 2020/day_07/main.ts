const rules: string[] = (await Deno.readTextFile(
  new URL(".", import.meta.url).pathname + "/input.txt",
)).split("\n").filter((x) => x);

class Bag {
  public contents: Map<string, number>;

  constructor(
    public color: string,
    contains: [string, number][],
  ) {
    this.contents = new Map(contains);
  }

  canContain(bag: string): boolean {
    return this.contents.has(bag);
  }
}

const bags: Bag[] = [];

for (const rule of rules) {
  const bagColor = rule.split(" ").slice(0, 2).join(" ");
  const contains = rule.split(" contain ")[1];

  const contents: [string, number][] = contains.trim().split(",").map(
    (item) => {
      if (item === "no other bags.") {
        return ["", 0];
      }

      const itemParts = item.trim().split(" ");
      const amount = itemParts.slice(0, 1).join("");
      const color = itemParts.slice(1, 3).join(" ").replace(/\.|s$/, "");

      return [color, parseInt(amount)];
    },
  );

  bags.push(new Bag(bagColor, contents));
}

const bagsFound = new Set();
const lookingFor = new Set(["shiny gold"]);
let part1 = 0;

while (lookingFor.size) {
  for (const color of lookingFor) {
    for (const bag of bags) {
      if (bag.canContain(color) && !bagsFound.has(bag.color)) {
        part1 += 1;

        lookingFor.add(bag.color);
        bagsFound.add(bag.color);
      }
    }

    lookingFor.delete(color);
  }
}

console.log(`Part 1: ${part1}`);

function getBagByColor(color: string): Bag {
  return bags.filter((bag) => bag.color === color)[0];
}

function countBags(color: string): number {
  const bag = getBagByColor(color);

  let count = 1;

  for (const [content, qunatity] of bag.contents) {
    if (!content) {
      continue;
    }

    count = count + (qunatity * countBags(content));
  }

  return count;
}

console.log("Part 2: " + (countBags("shiny gold") - 1));
