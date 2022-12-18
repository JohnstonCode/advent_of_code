package main

import (
	"fmt"
	"math"
	"os"
	"strings"
)

type cube struct {
	x int
	y int
	z int
}

var touchingChecks = []cube{
	{x: 1, y: 0, z: 0},
	{x: -1, y: 0, z: 0},
	{x: 0, y: 1, z: 0},
	{x: 0, y: -1, z: 0},
	{x: 0, y: 0, z: 1},
	{x: 0, y: 0, z: -1},
}

func main() {
	content, _ := os.ReadFile("./input.txt")
	cubes := make(map[cube]bool)
	part1 := 0
	min := cube{x: math.MaxInt, y: math.MaxInt, z: math.MaxInt}
	max := cube{x: math.MinInt, y: math.MinInt, z: math.MinInt}

	for _, line := range strings.Split(string(content), "\n") {
		var x, y, z int
		_, _ = fmt.Sscanf(line, "%d,%d,%d", &x, &y, &z)

		c := cube{x: x, y: y, z: z}
		cubes[c] = true

		min = cube{Min(min.x, c.x), Min(min.y, c.y), Min(min.z, c.z)}
		max = cube{Max(max.x, c.x), Max(max.y, c.y), Max(max.z, c.z)}
	}

	for c := range cubes {
		part1 += getNonTouchingSides(c, cubes)
	}

	fmt.Printf("Part 1: %v\n", part1)

	for x := min.x - 1; x <= max.x+1; x++ {
		for y := min.y - 1; y <= max.y+1; y++ {
			for z := min.z - 1; z <= max.z+1; z++ {
				cubes[cube{x, y, z}] = cubes[cube{x, y, z}]
			}
		}
	}

	queue := []cube{min}
	visited := map[cube]bool{}
	part2 := 0

	for len(queue) > 0 {
		curr := queue[0]
		queue = queue[1:]

		for _, check := range touchingChecks {
			nc := cube{x: curr.x + check.x, y: curr.y + check.y, z: curr.z + check.z}

			if c, ok := cubes[nc]; c {
				part2++
			} else if _, seen := visited[nc]; ok && !seen {
				visited[nc] = true
				queue = append(queue, nc)
			}
		}
	}

	fmt.Printf("Part 2: %v\n", part2)
}

func getNonTouchingSides(c cube, cubes map[cube]bool) int {
	exposedSides := 0

	for _, check := range touchingChecks {
		nc := cube{x: c.x + check.x, y: c.y + check.y, z: c.z + check.z}

		if _, ok := cubes[nc]; !ok {
			exposedSides++
		}
	}

	return exposedSides
}

func Min(a, b int) int {
	if a < b {
		return a
	}

	return b
}

func Max(a, b int) int {
	if a > b {
		return a
	}

	return b
}
