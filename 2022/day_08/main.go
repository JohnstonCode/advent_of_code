package main

import (
	"fmt"
	"image"
	"os"
	"strings"
)

func main() {
	content, _ := os.ReadFile("./input.txt")
	trees := map[image.Point]int{}

	for y, line := range strings.Fields(strings.TrimSpace(string(content))) {
		for x, tree := range line {
			trees[image.Point{X: x, Y: y}] = int(tree - '0')
		}
	}

	part1 := 0
	part2 := 0

	for point := range trees {
		vis := 0
		score := 1

		for _, dir := range []image.Point{{0, -1}, {1, 0}, {0, 1}, {-1, 0}} {
			for newPoint, i := point.Add(dir), 0; ; newPoint, i = newPoint.Add(dir), i+1 {
				if _, ok := trees[newPoint]; !ok {
					vis = 1
					score *= i
					break
				}

				if trees[newPoint] >= trees[point] {
					score *= i + 1

					break
				}
			}
		}

		part1 += vis
		if score > part2 {
			part2 = score
		}
	}

	fmt.Printf("Part 1: %v\n", part1)
	fmt.Printf("Part 2: %v\n", part2)
}
