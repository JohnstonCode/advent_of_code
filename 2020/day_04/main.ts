const batchFile: string = (await Deno.readTextFile(
  new URL(".", import.meta.url).pathname + "/input.txt",
)).trim();

class Passport {
  constructor(
    public byr: string | undefined,
    public iyr: string | undefined,
    public eyr: string | undefined,
    public hgt: string | undefined,
    public hcl: string | undefined,
    public ecl: string | undefined,
    public pid: string | undefined,
    public cid: string | undefined,
  ) {}

  static parse(raw: string): Passport {
    const [byr] = raw.match(/(?<=byr:)([^\s]+)/) || [];
    const [iyr] = raw.match(/(?<=iyr:)([^\s]+)/) || [];
    const [eyr] = raw.match(/(?<=eyr:)([^\s]+)/) || [];
    const [hgt] = raw.match(/(?<=hgt:)([^\s]+)/) || [];
    const [hcl] = raw.match(/(?<=hcl:)([^\s]+)/) || [];
    const [ecl] = raw.match(/(?<=ecl:)([^\s]+)/) || [];
    const [pid] = raw.match(/(?<=pid:)([^\s]+)/) || [];
    const [cid] = raw.match(/(?<=cid:)([^\s]+)/) || [];

    return new Passport(
      byr,
      iyr,
      eyr,
      hgt,
      hcl,
      ecl,
      pid,
      cid,
    );
  }

  public isValidPart1(): boolean {
    const requiredFields = [
      this.byr,
      this.iyr,
      this.eyr,
      this.hgt,
      this.hcl,
      this.ecl,
      this.pid,
    ];

    for (const field of requiredFields) {
      if (!field) {
        return false;
      }
    }

    return true;
  }

  public isValidPart2(): boolean {
    const byr = Number(this.byr);
    if (byr < 1920 || byr > 2002) {
      return false;
    }

    const iyr = Number(this.iyr);
    if (iyr < 2010 || iyr > 2020) {
      return false;
    }

    const eyr = Number(this.eyr);
    if (eyr < 2020 || eyr > 2030) {
      return false;
    }

    const [, num, mes] = this.hgt ? this.hgt.match(/(\d+)(\w{2})/) || [] : [];
    const number = Number(num);
    if (mes !== "cm" && mes !== "in") {
      return false;
    }

    if (mes === "cm" && (number < 150 || number > 193)) {
      return false;
    }

    if (mes === "in" && (number < 59 || number > 76)) {
      return false;
    }

    if (!/^#[0-9a-f]{6}$/.test(this.hcl || "")) {
      return false;
    }

    if (
      !["amb", "blu", "brn", "gry", "grn", "hzl", "oth"].includes(
        this.ecl || "",
      )
    ) {
      return false;
    }

    if (this.pid?.length !== 9 || !/([0-9]{9})/.test(this.pid || "")) {
      return false;
    }

    return true;
  }
}

const part1 =
  batchFile.split("\n\n").map((rawPassport) => Passport.parse(rawPassport))
    .filter((passport) => passport.isValidPart1()).length;

console.log(`Part 1: ${part1}`);

const part2 =
  batchFile.split("\n\n").map((rawPassport) => Passport.parse(rawPassport))
    .filter((passport) => passport.isValidPart1() && passport.isValidPart2()).length;

console.log(`Part 2: ${part2}`);
