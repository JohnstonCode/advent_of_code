const input: number[] = (await Deno.readTextFile(
  new URL(".", import.meta.url).pathname + "/input.txt",
)).split(",").filter((x) => x).map((num) => Number(num));

class GameNumber {
  constructor(
    public num: number,
    public played: number[] = [],
  ) {
  }

  public getAge(): number {
    return (this.played[this.played.length - 1] || 0) -
      (this.played[this.played.length - 2] || 0);
  }

  public addTurn(turn: number): void {
    this.played.push(turn);
  }
}

class Game {
  private turn = 1;
  private playedNumbers = new Map<number, GameNumber>();
  private lastNumber: GameNumber;

  constructor(
    private startingNumbers: number[],
  ) {
    const zero = new GameNumber(0);
    this.lastNumber = zero;
    this.playedNumbers.set(0, zero);

    startingNumbers.forEach((startingNumber) => {
      const gameNumber = new GameNumber(startingNumber, [this.turn]);
      this.playedNumbers.set(startingNumber, gameNumber);
      this.lastNumber = gameNumber;
      this.turn++;
    });
  }

  public playUntil(turn: number): number {
    while (this.turn <= turn) {
      if (this.lastNumber.played.length === 1) {
        this.lastNumber = this.getZero();
        this.getZero().addTurn(this.turn);
      } else if (!this.playedNumbers.has(this.lastNumber.getAge())) {
        const newNum = new GameNumber(this.lastNumber.getAge(), [this.turn]);
        this.playedNumbers.set(this.lastNumber.getAge(), newNum);

        this.lastNumber = newNum;
      } else {
        const num = this.playedNumbers.get(this.lastNumber.getAge());

        if (!num) {
          throw new Error();
        }

        num.addTurn(this.turn);

        this.lastNumber = num;
      }

      this.turn++;
    }

    return this.lastNumber.num;
  }

  private getZero(): GameNumber {
    const zero = this.playedNumbers.get(0);

    if (!zero) {
      throw new Error("Zero isnt in the played numbers");
    }

    return zero;
  }
}

const part1 = new Game(input);

console.log(`Part 1: ${part1.playUntil(2020)}`);

const part2 = new Game(input);

console.log(`Part 2: ${part2.playUntil(30000000)}`);
