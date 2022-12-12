package main

import (
	"fmt"
	"image"
	"math"
	"os"
	"strings"
)

func main() {
	content, _ := os.ReadFile("./input.txt")
	grid, start, end := createGrid(string(content))
	grid[start] = 'a'
	grid[end] = 'z'

	part1 := getFewestSteps(grid, start, end)
	fmt.Printf("Part 1: %v\n", part1)

	var starts []image.Point

	for s, v := range grid {
		if v == 'a' {
			starts = append(starts, s)
		}
	}

	part2 := math.MaxInt

	for _, s := range starts {
		steps := getFewestSteps(grid, s, end)

		if steps == 0 {
			continue
		}

		if steps < part2 {
			part2 = steps
		}
	}

	fmt.Printf("Part 2: %v\n", part2)
}

func createGrid(s string) (map[image.Point]rune, image.Point, image.Point) {
	grid := map[image.Point]rune{}
	var start, end image.Point

	for y, line := range strings.Fields(s) {
		for x, v := range line {
			grid[image.Point{X: x, Y: y}] = v

			if v == 'S' {
				start = image.Point{X: x, Y: y}
			} else if v == 'E' {
				end = image.Point{X: x, Y: y}
			}
		}
	}

	return grid, start, end
}

func getFewestSteps(grid map[image.Point]rune, start image.Point, end image.Point) int {
	queue := []image.Point{start}
	dist := map[image.Point]int{}

	for point := range grid {
		dist[point] = -1
	}

	dist[start] = 0

	for len(queue) > 0 {
		point := queue[0]
		queue = queue[1:]

		if end.Eq(point) {
			return dist[end]
		}

		for _, dir := range []image.Point{{0, -1}, {1, 0}, {0, 1}, {-1, 0}} {
			newPoint := point.Add(dir)

			if _, ok := grid[newPoint]; !ok {
				continue
			}

			if grid[newPoint]-grid[point] > 1 {
				continue
			}

			if dist[newPoint] != -1 {
				continue
			}

			dist[newPoint] = dist[point] + 1

			queue = append(queue, newPoint)
		}
	}

	return 0
}
