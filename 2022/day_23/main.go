package main

import (
	"fmt"
	"image"
	"math"
	"os"
	"strings"
)

var directions = map[int][]image.Point{
	0: {image.Point{X: 0, Y: -1}, image.Point{X: 1, Y: -1}, image.Point{X: -1, Y: -1}}, //North
	1: {image.Point{X: 0, Y: 1}, image.Point{X: 1, Y: 1}, image.Point{X: -1, Y: 1}},    //South
	2: {image.Point{X: -1, Y: 0}, image.Point{X: -1, Y: -1}, image.Point{X: -1, Y: 1}}, //West
	3: {image.Point{X: 1, Y: 0}, image.Point{X: 1, Y: -1}, image.Point{X: 1, Y: 1}},    //East
}

func main() {
	content, _ := os.ReadFile("./input.txt")
	grid := make(map[image.Point]string)

	for y, line := range strings.Split(string(content), "\n") {
		for x, v := range strings.Split(line, "") {
			grid[image.Point{X: x, Y: y}] = v
		}
	}

	fmt.Printf("Part 1: %v\n", simulate(grid, false))
	fmt.Printf("Part 2: %v\n", simulate(grid, true))
}

func simulate(g map[image.Point]string, p2 bool) int {
	grid := copyMap(g)
	currentDir := 0
	loopLen := 10

	if p2 {
		loopLen = math.MaxInt
	}

	for i := 0; i < loopLen; i++ {
		moves := map[image.Point][]image.Point{}

	loop:
		for point, val := range grid {
			if val == "." {
				continue
			}

			empty := true
			for _, dir := range directions {
				for _, d := range dir {
					np := point.Add(d)
					if v, ok := grid[np]; !ok {
						continue
					} else if v == "." {
						continue
					}

					empty = false
				}
			}
			if empty {
				continue loop
			}

			tmpDir := currentDir
			for j := 0; j < 4; j++ {
				empty = true
				for _, dir := range directions[tmpDir] {
					np := point.Add(dir)
					if v, ok := grid[np]; !ok {
						continue
					} else if v == "." {
						continue
					}

					empty = false
				}

				if empty {
					moves[point.Add(directions[tmpDir][0])] = append(moves[point.Add(directions[tmpDir][0])], point)

					tmpDir = (tmpDir + 1) % 4

					break
				}

				tmpDir = (tmpDir + 1) % 4
			}
		}

		if len(moves) == 0 && p2 {
			return i + 1
		}

		for to, froms := range moves {
			if len(froms) > 1 {
				continue
			}

			from := froms[0]

			grid[from] = "."
			grid[to] = "#"
		}

		currentDir = (currentDir + 1) % 4
	}

	res := 0
	minX, minY, maxX, maxY := getMinAndMax(grid)

	for y := minY; y <= maxY; y++ {
		for x := minX; x <= maxX; x++ {
			if v, ok := grid[image.Point{X: x, Y: y}]; !ok {
				res++
			} else if v == "." {
				res++
			}
		}
	}

	return res
}

func getMinAndMax(grid map[image.Point]string) (int, int, int, int) {
	minX, minY, maxX, maxY := 0, 0, 0, 0

	for point, v := range grid {
		if v == "#" {
			minX = min(minX, point.X)
			minY = min(minY, point.Y)
			maxX = max(maxX, point.X)
			maxY = max(maxY, point.Y)
		}
	}

	return minX, minY, maxX, maxY
}

func max(a, b int) int {
	if a > b {
		return a
	}

	return b
}

func min(a, b int) int {
	if a < b {
		return a
	}

	return b
}

func copyMap(m map[image.Point]string) map[image.Point]string {
	nm := make(map[image.Point]string)
	for k, v := range m {
		nm[k] = v
	}

	return nm
}
