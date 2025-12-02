package main

import (
	"fmt"
	"os"
	"strconv"
	"strings"
)

type rotation struct {
	dir    string
	amount int
}

func main() {
	contents, err := os.ReadFile("input.txt")
	if err != nil {
		panic(err)
	}

	var rotations []rotation

	lines := strings.Split(string(contents), "\n")
	for _, line := range lines {
		dir := string(line[0])
		amount, err := strconv.Atoi(line[1:])
		if err != nil {
			panic(err)
		}

		rotations = append(rotations, rotation{dir: dir, amount: amount})
	}

	part1(rotations)
	part2(rotations)
}

func part1(rotations []rotation) {
	pos := 50
	count := 0

	for _, r := range rotations {
		if r.dir == "L" {
			nextPos := pos - r.amount
			nextPos = nextPos % 100

			if nextPos == 0 {
				count++
			}

			pos = nextPos
		} else {
			nextPos := pos + r.amount
			nextPos = nextPos % 100

			if nextPos == 0 {
				count++
			}

			pos = nextPos
		}
	}

	fmt.Println(count)
}

func part2(rotations []rotation) {
	pos := 50
	count := 0

	for _, r := range rotations {
		if r.dir == "L" {
			if pos == 0 {
				count += r.amount / 100
			} else if r.amount >= pos {
				count += 1 + (r.amount-pos)/100
			}

			pos = ((pos-r.amount)%100 + 100) % 100
		} else {
			count += (pos + r.amount) / 100

			pos = (pos + r.amount) % 100
		}
	}

	fmt.Println(count)
}
