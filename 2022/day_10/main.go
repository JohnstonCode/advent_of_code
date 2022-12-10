package main

import (
	"fmt"
	"os"
	"strconv"
	"strings"
)

func main() {
	content, _ := os.ReadFile("./input.txt")

	c := 0
	x := 1
	part1 := 0
	part2 := ""

	for _, line := range strings.Split(string(content), "\n") {
		line = strings.TrimSpace(line)

		part2 += drawPixel(c, x)
		part1 += cycle(&c, x)

		if line == "noop" {
			continue
		}

		p := strings.Split(line, " ")
		v, _ := strconv.Atoi(p[1])

		part2 += drawPixel(c, x)
		part1 += cycle(&c, x)

		x += v
	}

	fmt.Printf("Part 1: %v\n", part1)
	fmt.Printf("Part 2: \n%v", part2)
}

func cycle(c *int, x int) int {
	if *c++; (*c+40/2)%40 == 0 {
		return *c * x
	}

	return 0
}

func drawPixel(c int, x int) string {
	var s string

	if c%40 >= x-1 && c%40 <= x+1 {
		s = "#"
	} else {
		s = "."
	}

	if c%40 == 39 {
		s += "\n"
	}

	return s
}
